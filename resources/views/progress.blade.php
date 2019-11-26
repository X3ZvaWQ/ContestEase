<style>
    div.progress-box {

    }

    div.progress-box::-webkit-scrollbar-button{
        display: none;
    }

    body > div.container-fluid > div > div.col-2{
        padding: 3rem 1rem!important;
        height: 100vh;
        overflow-y: auto;
    }

    body > div.container-fluid > div > div.col-2::-webkit-scrollbar {
        width: 6px;
    }

    body > div.container-fluid > div > div.col-2::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
        background: #bbb;
    }

    body > div.container-fluid > div > div.col-2::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
        border-radius: 10px;
        background: #eee;
    }

</style>

<div class="progress-box">
    
</div>


<script src="https://cdn.bootcss.com/echarts/4.4.0-rc.1/echarts.min.js"></script>
<script>
    window.addEventListener('load',function(){
        updateProgress();
        setInterval(function(){
            updateProgress();
        },5000);
    })
    function updateProgress()
    {
        $.ajax({
            type: 'POST',
            url: '/mark/progress',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(ret){
                console.log(ret);
                if(ret.ret == '200') {
                    data = ret.data;
                    $('div.progress-box').html('');
                    Object.keys(data).forEach(function(key){
                        $('div.progress-box').append(`
                            <div id="${key}" class="total pb-4">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: ${data[key].progress}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> 
                                <small class="text-muted">${data[key].title}(${data[key].group}) ${data[key].progress}% ${data[key].solved}/${data[key].all}(${data[key].marking})</small>
                            </div>
                        `);
                    });
                } else {
                    console.log(ret);
                    alert('progress读取炸了,F12打开控制台截图告诉我发生了什么吧(');
                }
            },
            error: function(ret){
                console.log(ret);
                alert('progress读取炸了,F12打开控制台截图告诉我发生了什么吧(');
            }
        });
    }
</script>
