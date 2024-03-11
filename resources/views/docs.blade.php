@extends('layouts.vertical')


@section('css')

@endsection

@section('breadcrumb')
<div class="row page-title">
    <div class="col-md-12">
        <h4 class="mb-1 mt-0">Documentation <span class="badge badge-soft-danger font-size-14">v1.0</span></h4>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-10 col-lg-9">
        <div>
            <div class="row" id="intro">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mt-1">Introduction</h5>
                            <p class="font-size-15">
                                Shreyu is a fully featured premium admin template built on top of
                                awesome Bootstrap 4.3.1. It's crafted using modern web technologies HTML5,
                                CSS3 and jQuery and integrated with laravel.
                                The theme includes beautiful dashboard, many ready to use hand
                                crafted components, widgets, pages, etc. The components can be used very
                                easily on any page.
                                The theme is fully responsive and customizable. This documentation
                                is guidelines for all users who can even be a beginner or experienced web
                                developer.
                            </p>
                            <p class="border-top pt-3 font-size-15">
                                Thank you very much for considering Shreyu. We really care for our
                                buyers and so in case if you are having any question or feedback,
                                please feel free to get back to us via email <a
                                    href="mailto:support@coderthemes.com">support@coderthemes.com</a>
                                or by filling out the contact form on
                                <a href="https://coderthemes.com" target="_blank">our website</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="" id="structure">What's included</h5>
                            <p>Extract the zip file you received after purchase and you would find
                                the exact below files and folders structure inside laravel folder:</p>

                            <ul class="list-unstyled">
                                <li>
                                    <code>shreyu</code>
                                    <ul class="list-unstyled">
                                        <li class="pl-3 text-muted">
                                            ├── <code>app</code>
                                            <ul class="ml-3 list-unstyled">
                                                <li class="pl-3 text-muted">├── <code>Console</code></li>
                                                <li class="pl-3 text-muted">├── <code>Exception</code></li>
                                                <li class="pl-3 text-muted">├── <code>Http</code></li>
                                                <li class="pl-3 text-muted">├── <code>Providers</code></li>
                                            </ul>
                                        </li>
                                        <li class="pl-3 text-muted">├── <code>bootstrap</code></li>
                                        <li class="pl-3 text-muted">├── <code>config</code></li>
                                        <li class="pl-3 text-muted">├── <code>database</code></li>
                                        <li class="pl-3 text-muted">
                                            ├── <code>public</code>
                                            <ul class="ml-3 list-unstyled">
                                                <li class="pl-3 text-muted">
                                                    ├── <code>assets</code> - compiled assets files
                                                    <ul class="ml-3 list-unstyled">
                                                        <li class="pl-3 text-muted">├── <code>css</code></li>
                                                        <li class="pl-3 text-muted">├── <code>fonts</code></li>
                                                        <li class="pl-3 text-muted">├── <code>images</code></li>
                                                        <li class="pl-3 text-muted">├── <code>js</code></li>
                                                        <li class="pl-3 text-muted">├── <code>libs</code></li>
                                                    </ul>
                                                </li>
                                                <li class="pl-3 text-muted">├── <code>index.html</code></li>
                                            </ul>
                                        </li>
                                        <li class="pl-3 text-muted">
                                            ├── <code>resources - source assets files</code>
                                            <ul class="ml-3 list-unstyled">
                                                <li class="pl-3 text-muted">├── <code>scss</code></li>
                                                <li class="pl-3 text-muted">├── <code>fonts</code></li>
                                                <li class="pl-3 text-muted">├── <code>images</code></li>
                                                <li class="pl-3 text-muted">├── <code>js</code></li>
                                                <li class="pl-3 text-muted">├── <code>views - all the pages</code></li>
                                            </ul>
                                        </li>
                                        <li class="pl-3 text-muted">├── <code>routes</code></li>
                                        <li class="pl-3 text-muted">├── <code>storage</code></li>
                                        <li class="pl-3 text-muted">├── <code>package.json</code></li>
                                        <li class="pl-3 text-muted">├── <code>webpack.mix.js</code></li>
                                        <li class="pl-3 text-muted">├── <code>composer.json</code></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- prerequisites -->
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 id="prerequisites">Pre-requisites</h5>
                            <p>We are using <a href="https://laravel.com/docs/6.x" target="_blank">Laravel v6</a>. 
                                Please follow below steps to install and setup all pre-requisites:</p>

                            <ol>
                                <li>
                                    <strong>Laravel</strong>
                                    <p>The Laravel framework has a few system requirements. All of these requirements
                                        are satisfied by the <a href="https://laravel.com/docs/6.x/homestead">Laravel
                                            Homestead</a> virtual
                                        machine. However, if you are not using Homestead, you will need to make sure
                                        your server meets the following requirements:</p>
                                    <ul class="mb-3">
                                        <li>PHP &gt;= 7.2.0</li>
                                        <li>BCMath PHP Extension</li>
                                        <li>Ctype PHP Extension</li>
                                        <li>JSON PHP Extension</li>
                                        <li>Mbstring PHP Extension</li>
                                        <li>OpenSSL PHP Extension</li>
                                        <li>PDO PHP Extension</li>
                                        <li>Tokenizer PHP Extension</li>
                                        <li>XML PHP Extension</li>
                                    </ul>
                                </li>

                                <li><strong>Composor</strong>
                                    <p>Make sure to have the <a href="https://getcomposer.org" target="_blank">Composor</a>
                                        installed &amp; running in
                                        your computer. If you already have installed Composor on your
                                        computer, you can skip this step.</p>
                                </li>

                                <li><strong>Nodejs</strong>
                                    <p>Make sure to have the <a href="https://nodejs.org/" target="_blank">Node.js</a>
                                        installed &amp; running in
                                        your computer. If you already have installed nodejs on your
                                        computer, you can skip this step.</p>
                                </li>
                                
                                <li><strong>Git</strong>
                                    <p>Make sure to have the <a href="https://git-scm.com/" target="_blank">Git</a>
                                        installed &amp; running in your
                                        computer. If you already have installed git on your
                                        computer, you can skip this step</p>
                                </li>

                                <li>
                                    <strong>Gulp</strong>
                                    <p>Make sure to have the <a href="https://gulpjs.com/" target="_blank">Gulp</a>
                                        installed &amp; running in your
                                        computer. If you already have installed gulp on your
                                        computer, you can skip this step.</p>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- setup -->
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 id="setup">Getting Started</h5>

                            <p class="mb-2">
                                Once you have all pre-requisites installed and running, you are
                                ready to get start with the template.
                                First, go to the root folder (the folder created when you extract
                                the zip) where the package.json is available and run
                                the following command:
                            </p>

                            <p class="bg-light p-2 mb-2 font-weight-bold"><code>composor install</code>
                            </p>

                            <p>This will download all required laravel and project related dependencies defined in composor.json file.</p>
                            
                            <p class="bg-light p-2 mb-2 font-weight-bold"><code>npm install</code>
                            </p>

                            <p>This will download all required dependencies defined in package.json
                                file. Once it finished running the command, it will generate a folder name
                                <code>node_modules</code> where you'll be able to see downloaded
                                packages.</p>

                            <p class="mb-2 mt-4">Now you're good to go in running the template and
                                view it in browser by running the command below:</p>
                            <p class="bg-light p-2 mb-2 font-weight-bold"><code>npm run dev</code> or <code>npm run watch</code>
                            </p>
                            <p>This would compile all the required assets and make them ready to use inside <code>public/assets</code> folder.</p>

                            <p class="bg-light p-2 mb-2 font-weight-bold"><code>php artisan serve</code></p>
                            <p>This command will start a development server at <a href="http://localhost:8000">http://localhost:8000</a></p>

                            <p class="mt-4 mb-2">You can also create a production ready build of assets by running the command below:</p>
                            <p class="bg-light p-2 mb-2 font-weight-bold"><code>npm run prod</code></p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- customization -->
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 id="customization">Customization</h5>

                            <p class="mb-4">
                                You can easily customize various elements including navbar, topbar,
                                form elements etc by simply editing Sass files available in
                                <code>/resources/scss</code> directory. You can also remove unneeded
                                components from <code>app.scss</code> file.
                            </p>

                            <div class="p-lg-1 mt-1"> 
                                <div class="">
                                    <h6 class="mb-2 font-size-15">How to change colors?</h6>

                                    <p class="mb-2">
                                        In order to change colors (including primary, secondary,
                                        success, info, danger, warning, etc.), do the following:
                                    </p>

                                    <ul>
                                        <li>A file
                                            <code>resources/scss/_variables.scss</code> is containing
                                            the overridden colors and other configurations of
                                            bootstrap.
                                            You can change any values here and it would get
                                            reflected in
                                            any bootstrap based components or elements.
                                        </li>
                                    </ul>
                                </div>

                                <div class="p-lg-1 mt-2">
                                    <h6 class="mb-2 font-size-15">How to change footer?</h6>
                                    <p>
                                        In order to add, change or remove any ui elements from
                                        footer or
                                        sidebar, simply edit in file
                                        <code>resources/views/layouts/shared/footer.html</code>. The change would
                                        be reflected in all files automatically.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- layouts -->
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 id="layouts">Layout and Sidebar</h5>

                            <p>
                                You can change or customize the overall layout or left side
                                navigation easily. There are examples available
                                for horizontal nav, dark theme, RTL, condensed side nav, fixed width
                                (boxed), etc. You can check them under layouts menu
                                option. Additionally, you can also modify the left side nav, topbar,
                                etc.
                            </p>

                            <div class="p-lg-1 mt-1">
                                <h6 class="mb-2 font-size-15">How to change left side nav width?
                                </h6>
                                <p>In order to change the width of left side navigation bar, open a
                                    file
                                    <code>resources/scss/_variables.scss</code> and change the value of
                                    variable
                                    <code>$leftbar-width</code>. The default value is set to
                                    <code>240px</code>.
                                </p>
                            </div>

                            <div class="p-lg-1 mt-1">
                                <h6 class="mb-2 font-size-15">How to change background or menu text
                                    color?</h6>
                                <p class="mb-1">In order to change the background color, open a file
                                    <code>resources/scss/_variables.scss</code> and change the value
                                    of
                                    variable
                                    <code>$bg-leftbar-light</code> the default light value is set to
                                    <code>#ffffff</code>, <code>$bg-leftbar-dark</code> the default
                                    dark
                                    value is set to
                                    <code>#323742</code>.
                                </p>

                                <p class="mb-1">
                                    When you change the background color, you might want to change
                                    the
                                    color of menu accordingly. To change
                                    the color of menu item, change the variable
                                    <code>$menu-item</code>
                                    (Default is set to <code>#4B4B5A</code>).
                                    Similarly, change the value of variables
                                    <code>$menu-item-hover</code> and
                                    <code>$menu-item-active</code>.
                                </p>
                                <p>
                                    You can change other styles by making modifications in
                                    <code>resources/scss/custom/structure/_left-menu.scss</code>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- change log -->
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 id="changelog">Change Log</h5>

                            <h6 class="font-size-15">
                                <span class="text-primary">1.0.0</span>
                                <span class="sub-header"> - 27 Oct, 2019 </span>
                            </h6>
                            <ul class="changlog-list">
                                <li>Initial release</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-lg-3">
        <div class="" id="sticky-list">
            <div id="list-navigation" class="list-group list-group-flush d-none d-lg-block">
                <a class="list-group-item list-group-item-action side-list-link active" href="#intro">Introduction</a>
                <a class="list-group-item list-group-item-action side-list-link" href="#structure">What's
                    included</a>
                <a class="list-group-item list-group-item-action side-list-link" href="#prerequisites">Prerequisites</a>
                <a class="list-group-item list-group-item-action side-list-link" href="#setup">Getting
                    Started</a>
                <a class="list-group-item list-group-item-action side-list-link" href="#customization">Customization</a>
                <a class="list-group-item list-group-item-action side-list-link" href="#layouts">Layout and
                    Sidebar</a>
                <a class="list-group-item list-group-item-action side-list-link" href="#changelog">Change Log</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection

@section('script-bottom')
<!-- sticky -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sticky/1.0.4/jquery.sticky.min.js"
    integrity="sha256-9p9wUORIjnIRp9PAyZGxql6KgJRNiH04y+8V4JjUhn0=" crossorigin="anonymous"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#sticky-list").sticky({
            topSpacing: 142
        });

        $(".side-list-link").on('click', function () {
            var target = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(target).offset().top - 100
            });

            $(".side-list-link").removeClass("active");
            $(this).addClass('active');
            return false;
        });
    });
</script>
@endsection