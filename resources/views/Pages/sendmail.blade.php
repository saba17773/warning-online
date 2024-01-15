@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-secondary ">
                <div class="card-header bg-success border-secondary text-white text-center">
                    <font size=4>รายละเอียดใบเตือน</font>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col ">ใบเตือน : <b>{{$warning_no}}</b></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 ">ชื่อ-สกุล : <b>{{$emplname}}</b></div>
                        <div class="col-md-6">รหัสพนักงาน : <b>{{$emplid}}</b></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 ">ตำแหน่ง : <b>{{$position}}</b></div>
                        <div class="col-md-6 ">ฝ่าย : <b>{{$division}}</b> ({{$divisioncode}})</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 ">สาเหตุ : <b>{{$corgroup_description}}</b></div>
                        <div class="col-md-6 ">แผนก : <b>{{$department}}</b></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-1"></div>
                        <div class="col-md-10 " id="cor"> </div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-6 ">วันที่กระทำผิด : <b>{{\Carbon\Carbon::parse($warning_date)->format('d/m/Y H:i')}}</b></div>
                        <div class="col-md-6 ">วันที่ตักเตือน : <b>{{\Carbon\Carbon::parse($follow_date)->format('d/m/Y H:i')}}</b></div>
                    </div>

                    <div class="row" id="penalty_div">

                    </div>


                    <br>
                    <center>
                        <div class="justify-content-left" id="emailboss"></div>

                        <br> <a href="/" class="btn btn-danger"><i class="fas fa-home"></i> กลับหน้าหลัก</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {


        if ('{{$penalty_start}}' === '') {
            $('#penalty_div').html();
        } else {
            $('#penalty_div').html("<font color=red><div class='col-md-12'>พักงาน : <b>" +
                "{{\Carbon\Carbon::parse($penalty_start)->format('d/m/Y')}}</b>  ถึง  " +
                "<b>{{\Carbon\Carbon::parse($penalty_end)->format('d/m/Y')}}</b></div></font>");
        }

        $.ajax({
            type: 'GET',
            url: '/getdata/corid',
            data: {
                data: '{{$warning_no}}'
            },
            dataType: "JSON",

            success: function(response) {
                var cordata = "";
                var i;
                for (i = 0; i < response.length; i++) {
                    var cordata = cordata + (i + 1) + ".) " + response[i].cor_description + "<br>";
                }
                $('#cor').html(cordata);
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Sentmail_user
        var source = {
            datatype: "json",
            datafields: [{
                    name: 'email',
                    type: 'string'
                },
                {
                    name: 'level_approved',
                    type: 'string'
                },
                {
                    name: 'type',
                    type: 'string'
                },
                {
                    name: 'status',
                    type: 'string'
                },
                {
                    name: 'status_description',
                    type: 'string'
                },
            ],
            url: '/getdata/email/{{$warning_no}}'
        };
        var dataAdapter = new $.jqx.dataAdapter(source);

        $("#emailboss").jqxGrid({
            width: 640,
            autoheight: true,
            source: dataAdapter,
            theme: 'custom',
            columns: [{
                    text: 'ผู้อนุมัติ',
                    datafield: 'email',
                    width: 300
                },
                {
                    text: 'ระดับอนุมัติ',
                    datafield: 'level_approved',
                    width: 70
                },
                {
                    text: 'ประเภท',
                    datafield: 'type',
                    width: 70
                },
                {
                    text: 'สถานะ',
                    datafield: 'status_description',
                    width: 100
                },
                {
                    text: 'Send Mail',
                    width: 100,
                    columntype: 'button',
                    cellsrenderer: function(row) {
                        var dataRecord = $("#emailboss").jqxGrid('getrowdata', row);

                        if (dataRecord.status == 0) {
                            return 'พร้อมส่ง';
                        }
                        if (dataRecord.status == 1) {
                            return 'ส่งอีกครั้ง';
                        }
                        if (dataRecord.status == 2) {
                            return 'อนุมัติแล้ว';
                        }
                        if (dataRecord.status == 5) {
                            return 'ไม่เห็นชอบ';
                        }
                    },
                    buttonclick: function(row) {
                        var dataRecord = $("#emailboss").jqxGrid('getrowdata', row);


                        if (dataRecord.status == 0 | dataRecord.status == 1) {

                            // console.log(dataRecord.type);
                            // alert('{{$warning_no}}'+dataRecord.email);

                            $.ajax({
                                type: "POST",
                                url: "/email/sendmail",
                                data: {
                                    warning_no: '{{$warning_no}}',
                                    email: dataRecord.email,
                                    level: dataRecord.level_approved,
                                    type: dataRecord.type
                                },
                                dataType: "JSON",
                                success: function(response) {
                                    if (response.result == true) {
                                        alert('ส่ง Email ได้สำเร็จ !\n' + dataRecord.email);
                                        location.reload();

                                    } else {
                                        alert('เกิดข้อผิดพลาด ! \nสาเหตุ : ' + response.message);
                                    }

                                }
                            });
                        }
                    },
                }
            ]
        });

    });
</script>

@endsection