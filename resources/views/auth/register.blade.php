@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-dark">
                <div class="card-header bg-primary border-dark text-white text-center"><font size=4>สมัครเข้าใช้งานระบบ</font></div>

                <div class="card-body">
                    
                        @csrf                     

                        <div class="form-group row">
                            <label for="emplid" class="col-md-4 col-form-label text-md-right">รหัสพนักงาน</label>
                        
                            <div class="col-md-6">
                               
                                <input id="emplid" {{ $errors->has('emplid') ? ' is-invalid' : '' }} type="number" class="form-control{{ $errors->has('emplid') ? ' is-invalid' : '' }}" name="emplid" value="{{ old('emplid') }}" readonly autofocus>                       
                                @if ($errors->has('emplid'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('emplid') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm">
                                <button id="search" type="button" class="btn btn-outline-danger"><i class="fas fa-search"></i> ค้นหา </button>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="userid" class="col-md-4 col-form-label text-md-right text-danger"><b>Username</b></label>

                            <div class="col-md-6">
                                <input id="userid" type="text" class="form-control{{ $errors->has('userid') ? ' is-invalid' : '' }}" name="userid" value="{{ old('userid') }}" required  readonly>

                                @if ($errors->has('userid'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('userid') }}</strong>
                                    </span>
                                @endif
                                <font color=red size=2><b>*ใช้ในการเข้าสู่ระบบ</b> </font>
                            </div>
                        </div>
                       
                
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">ชื่อ - สกุล</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus readonly>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required readonly>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company" class="col-md-4 col-form-label text-md-right">บริษัท</label>

                            <div class="col-md-6">
                                <input id="company" type="company" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="company" value="{{ old('company') }}" required readonly>

                                @if ($errors->has('company'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('company') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="division" class="col-md-4 col-form-label text-md-right">ฝ่าย</label>

                            <div class="col-md-6">
                                <input id="division" type="division" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="division" value="{{ old('division') }}" required readonly>

                                @if ($errors->has('division'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <hr noshade>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button id="regis_btn" class="btn btn-primary ">
                                    <i class="fas fa-user-plus"></i> สมัครใช้งาน </button>

                                <a href="/home" class="btn btn-danger float-right">กลับหน้าหลัก</a>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Modal -->
<div class="modal fade " id="search_modal" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title text-white " id="exampleModalLabel">ค้นหา</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div id="empl_grid"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">
            $(document).ready(function () {

                $('#regis_btn').on('click', function (event) {
                    if($('#emplid').val() == 0|$('#password').val() == ""){
                        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
                        return "STOP";
                    }

                    if($('#password').val() != $('#password-confirm').val() ){
                        alert('รหัสผ่านไม่ตรงกัน');
                        $('#password').val('');
                        $('#password-confirm').val('');
                        $('#password').focus();
                        return "STOP";
                    }

                    $.ajax({
                        type: 'POST',
                        url: '/regis',
                        data : 
                            {
                                '_token' : '{{ csrf_token() }}',
                                "emplid" : $('#emplid').val() ,
                                "userid" : $('#userid').val() ,
                                "name" : $('#name').val(), 
                                "email" : $('#email').val(), 
                                "company" : $('#company').val(),
                                "division" : $('#division').val(),
                                "password" : $('#password').val(),
                            }, 
                        dataType: "JSON", 
                        success: function(response)    
                            {
                                if(response.result == true){
                                    alert('สมัครใช้งานเรียบร้อยแล้ว');
                                    window.location = "/";
                                }else{
                                    alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.message);
                                    $('#password').val('');
                                    $('#password-confirm').val('');
                                    $('#password').focus();
                                }
                            }
                    });

                });

                $('#search').on('click', function (event) {

                    $('#search_modal').modal();

                    //Prepareing Data
                    var source =
                        {
                            datatype: "json",
                            datafields: [
                                // { name: 'CaseNum', type: 'string'},
                                { name: 'CODEMPID', type: 'string'},
                                { name: 'emplname', type: 'string'},
                                { name: 'EMAIL', type: 'string'},
                                { name: 'DIVISIONNAME', type: 'string'},
                                { name: 'DEPARTMENTNAME', type: 'string'},
                                { name: 'COMPANYNAME', type: 'string'},
                            ],
                            url: '/getdata/empl2/'
                        };
                        
                        var dataAdapter = new $.jqx.dataAdapter(source);
                    //Create Grid
                    $("#empl_grid").jqxGrid(
                        {
                            width: 760,
                            autoheight: true,
                            source: dataAdapter,
                            theme :'fresh',
                            showfilterrow: true,
                            filterable: true, 
                            pageable: true,
                            columnsresize: true,
                            columns: [
                            // { text: 'ID', datafield: 'id', width: 50 },
                            { text: 'รหัสพนักงาน', datafield: 'CODEMPID', width: 80 },
                            { text: 'ชื่อ - สกุล', datafield: 'emplname', width: 180 },
                            { text: 'ตำแหน่ง', datafield: 'DEPARTMENTNAME', width: 120 },
                            { text: 'ฝ่าย', datafield: 'DIVISIONNAME', width: 120 },
                            { text: 'บริษัท', datafield: 'COMPANYNAME', width: 60 },
                            { text: 'E-mail', datafield: 'EMAIL', width: 200 },
                            ]
                        });
                    $('#empl_grid').off("click").on('rowselect', function (event) 
                        {
                            var args = event.args;
                            var rowData = args.row;
                            console.log(rowData);
                            var userid   = (rowData.EMAIL).substring(0, (rowData.EMAIL).lastIndexOf("@"));
                            $('#userid').val(userid);
                            $('#emplid').val(rowData.CODEMPID);
                            $('#name').val(rowData.emplname);
                            $('#email').val(rowData.EMAIL);
                            $('#company').val(rowData.COMPANYNAME);
                            $('#division').val(rowData.DIVISIONNAME);
                            $("#search_modal .close").click()
                        });
                    // var emplid = $('#emplid').val() 
                    // $.ajax({   
                    // type: "GET",
                    // url: "/getdata/emplid/"+emplid,
                        
                    // dataType: "JSON",               
                    //     success: function(response){      

                    //         if (response.result == false){
                    //             swal({                    
                    //             title: "ไม่พบรหัสพนักงานในระบบ",
                    //             text:"กรุณาตรวจสอบอีกครั้ง",
                    //             icon: "error",
                    //             dangerMode: true,
                    //             });
                    //         }else{
                               
                    //             var userid   = (response.data[0]["EMAIL"]).substring(0, (response.data[0]["EMAIL"]).lastIndexOf("@"));
                    //             $('#userid').val(userid);
                    //             $('#name').val(response.data[0]["emplname"]);
                    //             $('#email').val(response.data[0]["EMAIL"]);
                    //             $('#company').val(response.data[0]["COMPANYNAME"]);
                    //             $('#division').val(response.data[0]["DIVISIONNAME"]);
                    //         }    
                    //     }
                
                    // });
                });         
            });
   </script>

@endsection
