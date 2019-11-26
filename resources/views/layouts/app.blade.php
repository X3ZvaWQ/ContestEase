<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SAST 评分系统</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Necessarily Declarations -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        <meta name="renderer" content="webkit">
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <link rel="alternate icon" type="image/png" href="https://static.1cf.co/img/atsast/favicon.png">
        <!-- loading curtain CSS -->
        <style>
            loading-curtain > div{
                text-align: center;
            }
            loading-curtain p{
                font-weight:100;
            }
            loading-curtain {
                display:flex;
                z-index:999;
                position:fixed;
                top:0;
                bottom:0;
                right:0;
                left:0;
                justify-content:center;
                align-items:center;
                background: #f5f5f5;
                transition:.2s ease-out .0s;
                opacity:1;
            }

            .lds-ellipsis {
                display: inline-block;
                position: relative;
                width: 64px;
                height: 64px;
            }
            .lds-ellipsis div {
                position: absolute;
                top: 27px;
                width: 11px;
                height: 11px;
                border-radius: 50%;
                background: rgba(0,0,0,.54);
                animation-timing-function: cubic-bezier(0, 1, 1, 0);
            }
            .lds-ellipsis div:nth-child(1) {
                left: 6px;
                animation: lds-ellipsis1 0.6s infinite;
            }
            .lds-ellipsis div:nth-child(2) {
                left: 6px;
                animation: lds-ellipsis2 0.6s infinite;
            }
            .lds-ellipsis div:nth-child(3) {
                left: 26px;
                animation: lds-ellipsis2 0.6s infinite;
            }
            .lds-ellipsis div:nth-child(4) {
                left: 45px;
                animation: lds-ellipsis3 0.6s infinite;
            }
            @keyframes lds-ellipsis1 {
                0% {
                    transform: scale(0);
                }
                100% {
                    transform: scale(1);
                }
            }
            @keyframes lds-ellipsis3 {
                0% {
                    transform: scale(1);
                }
                100% {
                    transform: scale(0);
                }
            }
            @keyframes lds-ellipsis2 {
                0% {
                    transform: translate(0, 0);
                }
                100% {
                    transform: translate(19px, 0);
                }
            }
        </style>
    </head>
    <body>
        <!-- Loading -->
        <loading-curtain>
            <div>
                <div class="lds-ellipsis">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <p>加载中,这次我引的是NOJ的资源，总不会再加载一分钟了吧……</p>
            </div>
        </loading-curtain>
        <!-- Loading Style -->
        <link rel="stylesheet" href="https://acm.njupt.edu.cn/static/library/bootstrap-material-design/dist/css/bootstrap-material-design.min.css">
        <link rel="stylesheet" href="https://acm.njupt.edu.cn/static/fonts/MDI-WXSS/MDI.css">

        <!--Header-->

        <style>
            paper-card {
                display: block;
                box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 30px;
                border-radius: 4px;
                transition: .2s ease-out .0s;
                color: #7a8e97;
                background: #fff;
                padding: 1rem;
                position: relative;
                border: 1px solid rgba(0, 0, 0, 0.15);
                margin-bottom: 2rem;
            }
            paper-card.img-card{
                padding:0;
                overflow: hidden;
                cursor: pointer;
            }

            paper-card.img-card > img{
                width:100%;
                height:10rem;
                object-fit: cover;
            }
            paper-card.img-card > div{
                text-align: center;
                padding: 1rem;
            }

            paper-card.album-selected {
                box-shadow: rgba(0, 0, 0, 0.35) 0px 0px 40px!important;
                transform: scale(1.02);
            }
            paper-card:hover {
                box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 40px;
            }
            h5{
                margin-bottom:1rem;
            }
            .form-control:disabled, .form-control[disabled]{
                background-color: transparent;
            }
            input{
                height: calc(2.4375rem + 2px);
            }
            #vscode_container{
                opacity: 0;
                transition: .2s ease-out .0s;
            }
            tips{
                display: block;
                font-size: .75rem;
                color: rgba(0,0,0,.26);
                margin-bottom: .5rem;
            }
            .atsast-toast {
                margin-bottom:5rem;
            }
            #snackbar-container{
                pointer-events: none;
            }
            #snackbar-container *{
                pointer-events: all;
            }
            ::-moz-selection {
                background: #b3d4fc;
                text-shadow: none
            }

            ::selection {
                background: #b3d4fc;
                text-shadow: none
            }
            .container {
                min-height:100vh;
                display: flex;
                align-items: center;
                padding-top:5rem;
                padding-bottom:5rem;
            }
            h5.pb-title {
                text-align:center;
                color: #7a8e97;
                margin-bottom:2rem;
            }
        </style>
        @yield('template')
        <!--Footer-->

        <script src="https://acm.njupt.edu.cn/static/library/jquery/dist/jquery.min.js"></script>
        <script src="https://acm.njupt.edu.cn/static/library/popper.js/dist/umd/popper.min.js"></script>
        <script src="https://acm.njupt.edu.cn/static/library/bootstrap-material-design/dist/js/bootstrap-material-design.min.js"></script>
        <script>
            $(document).ready(function () { $('body').bootstrapMaterialDesign(); });
            window.addEventListener("load",function() {
                $('loading-curtain').css({"opacity":"0","pointer-events":"none"});
            }, false);
        </script>
    </body>
</html>
