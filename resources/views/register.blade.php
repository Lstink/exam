<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>注册</title>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
</head>
<body>
    <form action="" id="myForm">
        <table>
            <tr>
                <td>手机号：</td>
                <td><input type="text" name="phone" id="phone"></td>
            </tr>
            <tr>
                <td>验证码：</td>
                <td><input type="text" name="code" id="code"><button>获取验证码</button></td>
            </tr>
            <tr>
                <td>密码：</td>
                <td><input type="password" name="password" id="password"></td>
            </tr>
            <tr>
                <td>确认密码：</td>
                <td><input type="password" name="repwd" id="repwd"></td>
            </tr>
            <tr>
                <td><input type="button" value="提交" id='btn'></td>
                <td><input type="reset" value="重置"></td>
            </tr>
        </table>
    </form>
</body>
</html>
<script>
    $(function(){
        
        //获取验证码
        $('button').click(function(){
            //获取手机号
            var phone = $('#phone').val();
            var reg = /^\d{11}$/;
            if (!reg.test(phone)) {
                alert('请输入十一位数字的手机号码');
                return false;
            }
            //请求发送验证码
            $.post(
                '{{ route('sendCode') }}',
                {phone:phone,_token:'{{ csrf_token() }}'},
                function(res){
                    if (res.code == 1) {
                        alert('验证码发送成功，请注意查收');
                    }else{
                        alert(res.message);
                    }
                },'json'
            );
            return false;
        });

        //提交表单
        $('#btn').click(function(){
            var phone = $('#phone').val();
            var code = $('#code').val();
            var password = $('#password').val();
            var repwd = $('#repwd').val();
            if (phone=='' || code=='' || password=='' || repwd=='') {
                return alert('请输入完整数据');
            }
            if (repwd !== password) {
                return alert('两次密码不一致');
            }
            $.post(
                '{{ route('register') }}',
                {phone:phone,code:code,password:password,_token:'{{ csrf_token() }}'},
                function(res){
                    alert(res.message);
                },'json'
            );
        });
    });
</script>