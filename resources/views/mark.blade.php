@extends('layouts.app')

@section('template')
<style>
    tips-span{
        display: block;
        font-size: .75rem;
        color: rgba(0,0,0,.26);
        margin-bottom: .5rem;
    }
    .hljs {
        display:block;
        overflow-x:auto;
        padding:0.5em;
        color:#383a42
    }
    .hljs-comment, .hljs-quote {
        color:#a0a1a7;
        font-style:italic
    }
    .hljs-doctag, .hljs-keyword, .hljs-formula {
        color:#a626a4
    }
    .hljs-section, .hljs-name, .hljs-selector-tag, .hljs-deletion, .hljs-subst {
        color:#e45649
    }
    .hljs-literal {
        color:#0184bb
    }
    .hljs-string, .hljs-regexp, .hljs-addition, .hljs-attribute, .hljs-meta-string {
        color:#50a14f
    }
    .hljs-built_in, .hljs-class .hljs-title {
        color:#c18401
    }
    .hljs-attr, .hljs-variable, .hljs-template-variable, .hljs-type, .hljs-selector-class, .hljs-selector-attr, .hljs-selector-pseudo, .hljs-number {
        color:#986801
    }
    .hljs-symbol, .hljs-bullet, .hljs-link, .hljs-meta, .hljs-selector-id, .hljs-title {
        color:#4078f2
    }
    .hljs-emphasis {
        font-style:italic
    }
    .hljs-strong {
        font-weight:bold
    }
    .hljs-link {
        text-decoration:underline
    }
    .atsast-empty {
        justify-content: center;
        align-items: center;
        height: 10rem;
    }
    #vscode_container{
        opacity: 0;
        transition: .2s ease-out .0s;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-2">
            @include('progress')
        </div>
        <div class="col-10">
            <div class="container">
                <div style="width:100%;">
                    <h5 class="pb-title"><i class="MDI script"></i> 欢迎，{{auth()->user()->name}} </h5>
                    <div class="mb-3 text-center">
                        <form style="display:inline-block" action="/logout" method="POST">
                            @csrf
                            <button class="bth btn-danger" type="submit">注销</button>
                        </form>
                    </div>
                    <paper-card class="mb-3">
                        <h5><i class="MDI note"></i> 题干</a></h5>
                        <div id="markdown_container"></div>
                    </paper-card>
                    <paper-card class="mb-3" id="review_area">

                        <h5><i class="MDI contacts"></i> 阅卷区域</a></h5>

                        <div class="mb-4">
                            <div class="row">
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <label for="pb_lang" class="bmd-label-floating">切换高亮</label>
                                        <select class="form-control" id="pb_lang" name="pb_lang" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <input type="hidden" name="answer_id" id="pb_answer_id"/>
                                    <div class="form-group">
                                        <label for="pb_score" class="bmd-label-floating">评分(0.0 ~ 10.0)</label>
                                        <input type="text" class="form-control" name="pb_score" id="pb_score" required>
                                    </div>
                                </div>
                            </div>
                            <tips-span>内容</tips-span>
                            <div id="vscode_container">
                                <div id="vscode" style="width:100%;height:30rem;border:1px solid grey"></div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" id="pb_submit" class="btn btn-outline-info" onclick="submit()">打分并切换至下一题</button>
                        </div>
                    </paper-card>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.bootcss.com/marked/0.7.0/marked.min.js"></script>
<script src="https://cdn.bootcss.com/highlight.js/9.15.10/highlight.min.js"></script>

<script>
    var editor;
    var user_id=0;
    var judging=false;

    window.addEventListener("load",function() {
        loadJsAsync("https://acm.njupt.edu.cn/static/library/monaco-editor/min/vs/loader.js");
        hljs.initHighlighting();
        requestAnswer();
    },false);

    function loadJsAsync(url){
        var body = document.getElementsByTagName('body')[0];
        var jsNode = document.createElement('script');

        jsNode.setAttribute('type', 'text/javascript');
        jsNode.setAttribute('src', url);
        body.appendChild(jsNode);

        jsNode.onload = function() {
            require.config({ paths: { 'vs': 'https://acm.njupt.edu.cn/static/library/monaco-editor/min/vs' }});

            window.MonacoEnvironment = {
                getWorkerUrl: function(workerId, label) {
                    return `data:text/javascript;charset=utf-8,${encodeURIComponent(`
                        self.MonacoEnvironment = {
                            baseUrl: 'https://acm.njupt.edu.cn/static/library/monaco-editor/min/'
                        };
                        importScripts('https://acm.njupt.edu.cn/static/library/monaco-editor/min/vs/base/worker/workerMain.js');`
                    )}`;
                }
            };

            require(["vs/editor/editor.main"], function () {
                editor = monaco.editor.create(document.getElementById('vscode'), {
                    value: "",
                    language: "plaintext",
                    readOnly:true,
                    wordWrap:"on"
                });
                $("#vscode_container").css("opacity",1);
                var all_lang=monaco.languages.getLanguages();
                all_lang.forEach(function (lang_conf) {
                    $("#pb_lang").append("<option value='"+lang_conf.id+"'>"+lang_conf.aliases[0]+"</option>");
                });
                $('#pb_lang').change(function(){
                    var targ_lang=$(this).children('option:selected').val();
                    monaco.editor.setModelLanguage(editor.getModel(), targ_lang);
                });
                monaco.editor.setModelLanguage(editor.getModel(), "plaintext");
                $("table").addClass("table");
            });
        }

    }

    function requestAnswer(data = {}){
        $.ajax({
            type: 'POST',
            url: '/mark/request',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            success: function(ret){
                console.log(ret);
                if(ret.ret == '200') {
                    data = ret.data;
                    $('#markdown_container').html(marked(data.problem));
                    $('label[for="pb_score"]').text(`评分(0.0 ~ ${data.max_score}.0)`)
                    $('#pb_answer_id').val(data.answer_id);
                    if(data.old_answer != null){
                        editor.setValue(data.answer + '\n\n\n\n\n==================旧的提交==================\n\n\n\n\n' + data.old_answer);
                    }else{
                        editor.setValue(data.answer);
                    }
                } else {
                    console.log(ret);
                    alert(ret.ret + ' : ' + ret.desc);
                }
            },
            error: function(ret){
                console.log(ret);
                alert('请求题目信息炸了,F12打开控制台截图告诉我发生了什么吧(');
            }
        });
    }

    function submit(){
        if($('#pb_score').val() == '' || isNaN(parseInt($('#pb_score').val()))){
            alert('请认真输入分数');
            return;
        }
        $.ajax({
            type: 'POST',
            url: '/mark/submit',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                answer_id: $('#pb_answer_id').val(),
                pb_score : $('#pb_score').val()
            },
            success: function(ret){
                console.log(ret);
                if(ret.ret == '200') {
                    requestAnswer();
                    $('#pb_score').val('');
                } else {
                    alert(ret.ret + ' : ' + ret.desc);
                }
            },
            error: function(ret){
                console.log(ret);
                alert('提交分数炸了,F12打开控制台截图告诉我发生了什么吧(');
            }
        });
    }
</script>
@endsection
