<?php

namespace Thinmartian\Cms\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\DatabaseManager;
use Symfony\Component\Filesystem\Filesystem;

class Build extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:build {--overwrite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile all YAML definitions and build the CMS.';

    /**
     * @var Illuminate\Contracts\Console\Kernel
     */
    protected $artisan;

    /**
     * @var Illuminate\Database\DatabaseManager
     */
    protected $db;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Kernel $artisan, DatabaseManager $db)
    {
        parent::__construct();
        $this->artisan = $artisan;
        $this->db = $db;
    }

    /**
     * Execute the console command. We add little sleeps inbetweeen tasks here to
     * let the user see what's happening, it's unreadable otherwise
     *
     * @return mixed
     */
    public function handle()
    {
        $bar = $this->setupBar();

        // delete the System folder so we can rebuild it
        $fs = new Filesystem();
        $fs->remove(app_path("Cms/System"));

        // build the directories first, to ensure correct perms
        $this->createDirectories();

        $bar->setMessage("<comment>Copying YAML definitions to app...</comment>");
        $bar->advance();
        $this->artisan->call("vendor:publish", [
            "--provider" => "Thinmartian\\Cms\\CmsServiceProvider",
            "--tag" => ["definitions"]
        ]);
        usleep(200000);

        $bar->setMessage("<comment>Generating database migrations from YAML definitions...</comment>");
        $bar->setMessage("<comment>Publishing core files...</comment>");
        $bar->advance();
        $this->artisan->call("vendor:publish", [
            "--provider" => "Thinmartian\\Cms\\CmsServiceProvider",
            "--tag" => ["migrations"]
        ]);
        $bar->advance();
        $this->artisan->call("cms:migrations");
        usleep(200000);

        $bar->setMessage("<comment>Generating models from YAML definitions...</comment>");
        $bar->advance();
        $this->artisan->call("cms:models", [
            '--overwrite' => $this->option('overwrite')
        ]);
        usleep(200000);

        $bar->setMessage("<comment>Generating controllers from YAML definitions...</comment>");
        $bar->advance();
        $this->artisan->call("cms:controllers");
        usleep(200000);

        $bar->setMessage("<comment>Publishing core files...</comment>");
        $bar->advance();
        $this->artisan->call("vendor:publish", [
            "--provider" => "Thinmartian\\Cms\\CmsServiceProvider"
        ]);
        $this->deleteUnused();
        usleep(200000);

        if ($this->option('overwrite')) {
            $bar->setMessage("<comment>Ensuring public assets are up-to-date...</comment>");
            $bar->advance();
            $this->artisan->call("vendor:publish", [
                "--provider" => "Thinmartian\\Cms\\CmsServiceProvider",
                "--tag" => ["assets"],
                "--force" => true
            ]);
            usleep(200000);
        }

        $bar->setMessage("<comment>Migrating database...</comment>");
        $bar->advance();
        $this->artisan->call("migrate");

        $bar->setMessage("<comment>Checking for an admin user...</comment>");
        $bar->advance();
        usleep(700000);
        if (! $this->db->table("cms_users")->where("access_level", "Admin")->count()) {
            $this->question("Please enter your CMS admin details");
            $this->requestAdmin();
            $bar->setMessage("<comment>Admin user created successfully</comment>");
        } else {
            $bar->setMessage("<comment>Admin user already exists</comment>");
        }

        // Remove the .gitignore in the app/Cms folder
        if (file_exists(app_path("Cms/.gitignore"))) {
            unlink(app_path("Cms/.gitignore"));
        }

        $bar->advance();
        usleep(400000);

        $bar->setMessage("<info>CMS build complete</info>");
        $bar->finish();

        $this->comment("Optimising application...");
        exec("composer dump");
        $this->artisan->call("optimize");
    }

    /**
     * When laravel does a vendor:publish we just copy all files across
     * but some of these are not used in the new location, they are called
     * from the vendor folder, so just delete these copied ones to stop
     * people getting confused.
     */
    private function deleteUnused()
    {
        if (file_exists(app_path("Cms/System/Http/Controllers/Controller.php"))) {
            unlink(app_path("Cms/System/Http/Controllers/Controller.php"));
        }
        if (file_exists(app_path("Cms/System/.gitignore"))) {
            unlink(app_path("Cms/System/.gitignore"));
        }
        if (file_exists(app_path("Cms/System/Http/Controllers/.gitignore"))) {
            unlink(app_path("Cms/System/Http/Controllers/.gitignore"));
        }
        if (file_exists(app_path("Cms/System/Http/Controllers/Auth"))) {
            $fs = new Filesystem();
            $fs->remove(app_path("Cms/System/Http/Controllers/Auth"));
        }
        if (file_exists(app_path("Cms/System/Model.php"))) {
            unlink(app_path("Cms/System/Model.php"));
        }
        if (file_exists(app_path("Cms/System/Setter.php"))) {
            unlink(app_path("Cms/System/Setter.php"));
        }
        if (file_exists(app_path("Cms/System/CmsMediumImage.php"))) {
            unlink(app_path("Cms/System/CmsMediumImage.php"));
        }
        if (file_exists(app_path("Cms/System/CmsMediumVideo.php"))) {
            unlink(app_path("Cms/System/CmsMediumVideo.php"));
        }
        if (file_exists(app_path("Cms/System/CmsMediumDocument.php"))) {
            unlink(app_path("Cms/System/CmsMediumDocument.php"));
        }
        if (file_exists(app_path("Cms/System/CmsMediumEmbed.php"))) {
            unlink(app_path("Cms/System/CmsMediumEmbed.php"));
        }
    }

    /**
     * Build the directories first, so they get the correct perms
     */
    private function createDirectories()
    {
        if (! file_exists(app_path("Cms"))) {
            mkdir(app_path("Cms"), 0777);
        }
        if (! file_exists(app_path("Cms/Definitions"))) {
            mkdir(app_path("Cms/Definitions"), 0777);
        }
        if (! file_exists(app_path("Cms/System"))) {
            mkdir(app_path("Cms/System"), 0777);
        }
        if (! file_exists(app_path("Cms/System/Http/Controllers"))) {
            mkdir(app_path("Cms/System/Http/Controllers"), 0777, true);
        }
        if (! file_exists(app_path("Cms/Http"))) {
            mkdir(app_path("Cms/Http"), 0777);
        }
        if (! file_exists(app_path("Cms/Http/Controllers"))) {
            mkdir(app_path("Cms/Http/Controllers"), 0777);
        }

        if (! file_exists(storage_path("app/cms"))) {
            mkdir(storage_path("app/cms"), 0777, true);
        }
        if (! file_exists(storage_path("app/cms/temp"))) {
            mkdir(storage_path("app/cms/temp"), 0777, true);
        }
        if (! file_exists(storage_path("app/cms/media"))) {
            mkdir(storage_path("app/cms/media"), 0777, true);
        }
    }

    /**
     * Create the admin account
     *
     * @return void
     */
    private function requestAdmin()
    {
        $credentials = [];
        $credentials["firstname"] = $this->ask("What is you first name?", "Steve");
        $credentials["surname"] = $this->ask("What is your surname?", "McKeogh");
        $credentials["email"] = $this->ask("What is your email address?", "steve@codegent.com");
        $credentials["password"] = $this->secret("Enter a password");
        $credentials["password_confirmation"] = $this->secret("Confirm your password");
        $credentials["password_confirmed"] = false;
        while ($credentials["password_confirmed"] === false) {
            $credentials = $this->confirmPassword($credentials);
        }
        $credentials["password"] = bcrypt($credentials["password"]);
        $this->createAdmin($credentials);
        return $credentials;
    }

    private function createAdmin($credentials)
    {
        $this->db->table("cms_users")->insert(
            array_merge(
                array_except($credentials, ["password_confirmed", "password_confirmation"]),
                ["access_level" => "Admin", "created_at" => $this->db->raw("now()"), "updated_at" => $this->db->raw("now()")]
            )
        );
    }

    /**
     * Confirm the passwords match
     *
     * @param  array $credentials
     * @return array
     */
    private function confirmPassword($credentials)
    {
        if ($credentials["password"] != $credentials["password_confirmation"]) {
            $this->error("Your passwords didn't match, please try again!");
            $credentials["password"] = $this->secret("Enter a password");
            $credentials["password_confirmation"] = $this->secret("Confirm your password");
        } else {
            $credentials["password_confirmed"] = true;
        }
        return $credentials;
    }

    /**
     * Return a progress bar
     *
     * @return Symfony\Component\Console\Helper\ProgressBar
     */
    private function setupBar($count = 9)
    {
        $bar = $this->output->createProgressBar($count);
        $bar->setFormat("%message% (%current%/%max%)\n%bar% %percent:3s%%\n");
        $bar->setBarWidth(50);
        return $bar;
    }
}
