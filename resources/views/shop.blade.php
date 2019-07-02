<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>demo</title>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
</head>
<body>
    <script>
        $(function(){
            $.ajax({
                url:'http://www.exam.com',
                type: 'get',
                success:function(res){
                    $('body').prepend('跨域请求成功'+res);
                }
            });
        });
    </script>
</body>
</html>