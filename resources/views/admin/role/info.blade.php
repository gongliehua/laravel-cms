<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>后台管理</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/ionicons/2.0.0/css/ionicons.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/select2/4.0.5/css/select2.min.css">
    <!-- toastr -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/2.1.4/toastr.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('static/admin/dist/css/AdminLTE.min.css') }}">

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
        角色信息
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="javascript:void(0)" onclick="top.location.href='{{ url('admin/index') }}'"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li><a href="javascript:void(0)">管理员管理</a>></li>
        <li><a href="{{ url('admin/roleList') }}">角色列表</a></li>
        <li class="active">角色信息</li>
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
                            <label for="name">名称</label> @if ($errors->has('name')) {{ $errors->first('name') }} @endif
                            <input type="text" name="name" class="form-control" id="name" value="{{ $role->name }}" placeholder="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="status">状态</label> @if ($errors->has('status')) {{ $errors->first('status') }} @endif
                            <select name="status" class="form-control select2" style="width: 100%;">
                                <option value="1" @if ($role->status === 1) selected @endif>正常</option>
                                <option value="0" @if ($role->status === 0) selected @endif>禁用</option>
                            </select>
                        </div>
                        <div class="form-group" id="rules">
                            <label>权限列表</label> @if ($errors->has('permission_id')) {{ $errors->first('permission_id') }} @endif
                            @if (count($permissions) > 0)
                                @foreach ($permissions as $key=>$value)
                                    <div class="checkbox"><label><input type="checkbox" name="permission_id[]" value="{{ $value->id }}" data-id="{{ $value->id }}" data-parentid="{{ $value->parent_id }}" @if (in_array($value->id,$permission_id)) checked @endif>@if ($value->parent_id === 0) ｜ @endif {{ str_repeat('－',$value->level*4) }} {{ $value->title }}</label></div>
                                @endforeach
                            @else
                                <div class="checkbox"><label><input type="checkbox">暂无权限</label></div>
                            @endif
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
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<!-- toastr -->
<script src="https://cdn.bootcss.com/toastr.js/2.1.4/toastr.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://cdn.bootcss.com/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.bootcss.com/select2/4.0.5/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('static/admin/dist/js/adminlte.min.js') }}"></script>
<!-- Diy js -->
<script>
    $(function(){
        $('.select2').select2();
        $('input[type="checkbox"]').click(function(){
            if($(this).prop('checked')) {
                $('input[data-parentid="'+$(this).attr('data-id')+'"]').prop('checked',true);
                $('input[data-id="'+$(this).attr('data-parentid')+'"]').prop('checked',true);
            }
        });
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
            img.src = '{{ asset('static/admin/dist/img/user2-160x160.jpg') }}';
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
