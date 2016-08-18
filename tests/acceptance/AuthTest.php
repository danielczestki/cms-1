<?php

require(realpath(__DIR__ . "/../support/MailTracking.php"));

use App\Cms\CmsUser;

class AuthTest extends TestCase
{
    
    use MailTracking;
    
    /**
     * Fake token for forgot passwords
     */
    const TOKEN = 123456789;
    
    //
    // Login
    // 
    
    /** @test */
    public function it_checks_the_login_route_loads()
    {
        $this->visit("/admin/login")
             ->see("login");
    }
    
    /** @test */
    public function it_doesnt_let_you_login_with_an_empty_form()
    {
        $this->visit("/admin/login")
             ->type("", "email")
             ->type("", "password")
             ->press("Login")
             ->seePageIs("/admin/login")
             ->see("is required");
    }
    
    /** @test */
    public function it_doesnt_let_you_login_with_invalid_credentials()
    {
        $this->migrate();
        $this->visit("/admin/login")
             ->type("joe@domain.com", "email")
             ->type("wrongpassword", "password")
             ->press("Login")
             ->seePageIs("/admin/login")
             ->see("do not match our records");
    }
    
    /** @test */
    public function it_lets_you_login()
    {
        $this->migrate();
        $user = $this->create_new_user();
        $this->visit("/admin/login")
             ->type($user->email, "email")
             ->type("secretpassword", "password")
             ->press("Login")
             ->seePageIs("/admin")
             ->see($user->firstname);
    }
    
    /** @test */
    public function it_prevents_logged_in_users_seeing_the_login_page()
    {
        $this->migrate();
        $user = $this->create_new_user();
        $this->actingAs($user, "cms")
             ->visit("/admin/login")
             ->seePageIs("/admin");
    }
    
    //
    // Logout
    //
    
    /** @test */
    public function it_checks_members_can_logout()
    {
        $this->migrate();
        $user = $this->create_new_user();
        $this->actingAs($user, "cms")
             ->visit("/admin/logout")
             ->seePageIs("/admin/login");
    }
    
    /** @test */
    public function it_checks_guests_cannot_logout()
    {
        $this->visit("/admin/logout")
             ->seePageIs("/admin/login");
    }
    
    //
    // Forgot password
    // 
    
    /** @test */
    public function it_checks_the_forgot_password_link_works()
    {
        $this->visit("/admin/login")
             ->click("reset it")
             ->seePageIs("/admin/password/reset")
             ->see("Send Password Reset Link");
    }
    
    /** @test */
    public function it_doesnt_let_you_request_a_password_reset_email_with_an_empty_form()
    {
        $this->visit("/admin/password/reset")
             ->press("Send Password Reset Link")
             ->see("required");
    }
    
    /** @test */
    public function it_doesnt_let_you_request_a_password_reset_email_with_an_invalid_email()
    {
        $this->migrate();
        $this->visit("/admin/password/reset")
             ->type("joe@wrongdomain.com", "email")
             ->press("Send Password Reset Link")
             ->see("can't find a user");
    }
    
    /** @test */
    public function it_sends_you_a_reset_password_link_email()
    {
        $this->migrate();
        $user = $this->create_new_user();
        // ensure the config has an email address, we don't need errors on that
        config(["mail.from" => [
            "address" => $user->email,
            "name" => "{$user->firstname} {$user->surname}"
        ]]);
        $this->visit("/admin/password/reset")
             ->type($user->email, "email")
             ->press("Send Password Reset Link")
             ->seeEmailWasSent()
             ->seeEmailTo($user->email)
             ->see("your password reset link");
    }
    
    /** @test */
    public function it_checks_the_reset_password_page_works()
    {
        $this->migrate();
        $user = $this->create_new_user();
        $reset = $this->create_new_password_reset($user);
        $link = "/admin/password/reset/". self::TOKEN ."?email=" . urlencode($user->email);
        
        // it shouldn't work
        $this->visit($link)
             ->press("Reset Password")
             ->seePageIs($link)
             ->see("is required");
        
        // it should work...
        $this->visit($link)
             ->type($user->email, "email")
             ->type("newpassword", "password")
             ->type("newpassword", "password_confirmation")
             ->press("Reset Password")
             ->seePageIs("/admin")
             ->see($user->firstname);
    }
    
    /** @test */
    public function it_prevents_logged_in_users_seeing_the_forgot_password_page()
    {
        $this->migrate();
        $user = $this->create_new_user();
        $this->actingAs($user, "cms")
             ->visit("/admin/password/reset")
             ->seePageIs("/admin");
    }
    
    //
    // Utils
    // 
    
    /**
     * Generate a user for testing
     * 
     * @return Illuminate\Database\Eloquent\Model
     */
    private function create_new_user()
    {
        $id = DB::table("cms_users")->insertGetId([
            "firstname" => "Joe",
            "surname" => "Bloggs",
            "email" => "joe@domain.com",
            "password" => bcrypt("secretpassword"),
            "created_at" => new \Carbon\Carbon,
            "updated_at" => new \Carbon\Carbon
        ]);
        return CmsUser::find($id);
    }
    
    /**
     * Generate a fake password reset record
     * 
     * @param  CmsUser $user
     * @return void
     */
    private function create_new_password_reset(CmsUser $user)
    {
        $reset = DB::table("cms_password_resets")->insert([
            "email" => $user->email,
            "token" => self::TOKEN,
            "created_at" => new \Carbon\Carbon
        ]);
    }
    
}
