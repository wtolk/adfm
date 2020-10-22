<!DOCTYPE html>
<html lang="{{  app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Админ панель</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/adfm/adfm-panel.css') }}">
{{--    <link rel="stylesheet" href="/panel/admin/css/bootstrap.min.css">--}}
{{--    <link rel="stylesheet" href="/panel/admin/css/style.css">--}}
{{--    <link rel="stylesheet" href="/panel/admin/css/admin-menu.css">--}}

    <!-- Nestable -->
{{--    <link rel="stylesheet" href="/panel/admin/css/selectize.default.css">--}}
{{--    <link rel="stylesheet" href="/panel/admin/css/nestable.css">--}}


{{--    <link rel="stylesheet" href="/panel/admin/css/custom.css">--}}
{{--    <link rel="stylesheet" href="/panel/admin/css/flatpickr.min.css">--}}
{{--    <link rel="stylesheet" href="/panel/admin/css/selectize.default.css">--}}



    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>

    <script type="text/javascript" href="/panel/plugins/ckeditor/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('editor1', {
                filebrowserBrowseUrl: '/filemanager',
                allowedContent: true
            });
        });
    </script>
{{--    <script src="/panel/admin/js/selectize.min.js" type="text/javascript"></script>--}}
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
{{--    {% include 'admin-menu.twig' %}--}}

    <aside class="sideBar"><img id="sidebg" src="/wallpaper.jpg">
        <div class="side-menu">
            <h3>ADFM CMS</h3>

{{--            {% set admin_menu = getAdminMenuItems() %}--}}

{{--            {% for item in admin_menu.standart %}--}}
{{--            <a href="/admin/{{item.link}}"><i class="fa {{item.fa_ico}}"></i> <span>{{item.title}}</span> </a>--}}

{{--            {% if item.children|length > 0 %}--}}
{{--            <div class="sidebar-submenu">--}}
{{--                <ul class="sidebar-menu">--}}
{{--                    {% for child in item.children %}--}}
{{--                    <li>--}}
{{--                        <a href="/admin/{{child.link}}">--}}
{{--                            <i class="fa {{child.fa_ico}}"></i>--}}
{{--                            <span>{{child.title}}</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    {% endfor %}--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--            {% endif %}--}}

{{--            {% endfor %}--}}

{{--            {% for item in admin_menu.modules %}--}}
{{--            <hr class="delimeter">--}}
{{--            <a href="/admin/{{item.link}}">--}}
{{--                <i class="fa {{item.fa_ico}}"></i>--}}
{{--                <span>{{item.title}}</span>--}}
{{--            </a>--}}
{{--            {% if item.submenu %}--}}
{{--            <div class="sidebar-submenu">--}}
{{--                <ul class="sidebar-menu">--}}
{{--                    {% for sub in item.submenu %}--}}
{{--                    <li><a href="/admin/{{sub.link}}"><i class="fa {{sub.fa_ico}}"></i><span>{{sub.title}}</span></a></li>--}}
{{--                    {% endfor %}--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--            {% endif %}--}}

{{--            {% if loop.last %}--}}
{{--            <hr class="delimeter">--}}
{{--            {% endif %}--}}
{{--            {% endfor %}--}}

            <!--<li><a href="/admin/files"><i class="fa-file fa"></i><span>Файлы</span></a></li>-->
            <a href="/admin/settings"><i class="fa-gear fa"></i><span>Конфигурация</span></a>
            <div class="sidebar-submenu">
                <ul class="sidebar-menu">
                    <li><a href="/admin/settings/redirects"><i class="fa"></i><span>Перенаправления</span></a></li>
                </ul>
            </div>
            <a href="/admin/users"><i class="fa-users fa"></i><span>Пользователи</span></a>
            <a href="/admin/roles"><i class="fa-user-secret fa"></i><span>Роли</span></a>
        </div>

    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="main-page">
        <!-- Content Header (Page header) -->

{{--        {{ getBanner()|raw }}--}}

        <div class="page-content-wrapper">
            @yield('content')
        </div>
    </div>


</div>

<!-- ./wrapper -->
<script>

    if(window.matchMedia('(max-width: 768px)').matches)
    {
        let val = 90;
        $('body').append(`
                <span class="sandwitch">|||</span>
                `);
        $('span.sandwitch').css({transform: 'rotate(' + val + 'deg)'});
        $('span.sandwitch').on('click', function () {
            $(this).css('display', 'none');
            $('aside.sideBar').css({
                'position': 'fixed',
                'margin-left': '0px',
                'z-index': 100,
            });
            $(document).mouseup(function (e) {
                let container = $('aside.sideBar');
                if (container.has(e.target).length === 0){
                    container.css({
                        'margin-left': '-200px',
                    });
                }
                $('span.sandwitch').css('display', 'block');
            });
        });
    } else {
        $('span.sandwitch').css('display', 'none');
        $('aside.sideBar').css({
            'position': 'fixed',
            'margin-left': '0px',
            'z-index': 100,
        });
    }



</script>

{{--{% block script %}--}}
{{--{% endblock %}--}}

<!-- Скрипт загрузки файлов -->
{{--<script src="/panel/admin/js/filepanel.js"></script>--}}
{{--<script src="/panel/admin/js/flatpickr.js"></script>--}}
{{--<script src="/panel/admin/js/ru.js"></script>--}}

<!-- Bootstrap 3.3.5 -->
{{--<script src="/panel/admin/js/bootstrap.min.js"></script>--}}

{{--<script src="/panel/plugins/ckeditor/ckeditor.js"></script>--}}
{{--<script src="/panel/admin/js/jquery.nestable.js"></script>--}}
<!-- custom script -->
{{--<script src="/panel/admin/js/custom.js"></script>--}}

<!-- Font Awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

</body>
</html>


