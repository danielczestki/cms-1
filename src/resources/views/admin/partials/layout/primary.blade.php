<nav class="Primary" v-on:mouseenter="primary_hover('over')" v-on:mouseleave="primary_hover('out')">
    <div class="Primary__body"><div class="Primary__overflow">
        <ul class="Primary__options">
            @foreach (CmsYaml::getNav() as $idx => $nav)
                <li class="Primary__option">
                    <a href="{{ $nav['url'] }}" class="Primary__link{{ in_nav($nav['controller']) ? ' Primary__link--selected' : null }}">
                        <i class="Primary__icon fa fa-{{ $nav['icon'] }}"></i>
                        <span class="Primary__label">{{ $nav["title"] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div></div>
</nav>