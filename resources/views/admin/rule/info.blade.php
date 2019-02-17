<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
    <!-- toastr -->
    <link rel="stylesheet" href="{{ asset('bower_components/toastr/build/toastr.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">

<section class="content-header">
    <h1>
        查看权限
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="javascript:void(0)" onclick="top.location.href='{{ url('admin/index') }}'"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li><a href="javascript:void(0)">用户管理</a>></li>
        <li><a href="{{ url('admin/rule') }}">权限列表</a></li>
        <li class="active">查看权限</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <!-- form start -->
                <form role="form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="name">名称</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ $rule->name }}" placeholder="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="title">标题</label>
                            <input type="text" name="title" class="form-control" id="title" value="{{ $rule->title }}" placeholder="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="icon">图标</label>
                            <input type="text" name="icon" class="form-control" id="icon" value="{{ $rule->icon }}" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="status">状态</label>
                            <select name="status" class="form-control select2" style="width: 100%;">
                                <option value="1" @if ($rule->status === 1) selected @endif>正常</option>
                                <option value="0" @if ($rule->status === 0) selected @endif>禁用</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ismenu">菜单栏</label>
                            <select name="ismenu" class="form-control select2" style="width: 100%;">
                                <option value="1" @if ($rule->status === 1) selected @endif>是</option>
                                <option value="0" @if ($rule->status === 0) selected @endif>否</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sort">排序</label>
                            <input type="text" name="sort" class="form-control" id="sort" value="{{ $rule->sort }}" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>上级权限</label>
                            <select name="parent_id" class="form-control select2" style="width: 100%;">
                                <option value="0">｜顶级权限</option>
                                @if (count($rules) > 0)
                                    @foreach ($rules as $key=>$value)
                                        <option value="{{ $value->id }}" @if ($value->id === $rule->parent_id) selected @endif> @if ($value->parent_id === 0) ｜ @endif {{ str_repeat('－',$value->level*4) }} {{ $value->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    {{--<div class="box-footer">--}}
                        {{--{{ csrf_field() }}--}}
                        {{--<button type="submit" class="btn btn-primary">提交</button>--}}
                    {{--</div>--}}
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
</section>

<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- toastr -->
<script src="{{ asset('bower_components/toastr/build/toastr.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- Diy js -->
<script>
    $(function(){
        $('.select2').select2();
    });

    function uploadImg(file,imgTag){
        var img = document.getElementById(imgTag);
        if(file.files && file.files[0]) {
            var reader = new FileReader();
            reader.onload = function (evt) {
                img.src = evt.target.result;
            };
            reader.readAsDataURL(file.files[0]);
        } else {
            img.src = '{{ asset('dist/img/user2-160x160.jpg') }}';
        }
    };

    toastr.options = {closeButton: true,progressBar: true};
    $(function () {
        @if ($errors->has('success'))
        toastr.success('{{ $errors->first('success') }}');
        @endif
        @if ($errors->has('error'))
        toastr.error('{{ $errors->first('error') }}');
        @endif

    });
</script>

</body>
</html>
