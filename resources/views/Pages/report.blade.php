<!DOCTYPE html>
<html>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">.

<head>

    <style>
        /* @page {
    size: A4 landscape;
    margin: 1cm;
}
@page :header { content: none ;} 
@page :footer { content: none ;}  */


        body {
            background: white;
        }

        #menu {
            display: none;
        }

        #wrapper,
        #content {
            width: auto;
            border: 0;
            margin: 0 5%;
            padding: 0;
            float: none !important;
        }
    </style>

    <link href="/css/app.css" type="text/css" rel="stylesheet">
    <link href="/css/all.css" type="text/css" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="{{ asset('js/printThis.js') }}"></script> --}}
    <script src="{{ asset('js/moment.js') }}"></script>


</head>

<body>

    <div class="container">
        <div class="row ">
            <div class="col-sm-3">
                <font size="3">เลขที่ใบเตือน : <b>{{$warning_no}}</b></font>

            </div>
            <div class="col-sm-6 text-center align-self-center">
                <h3>
                    <div id="reportcompany">Company</div>
                </h3>
                <h4>หนังสือเตือน</h4>
            </div>
            <div class="col-sm-3 text-right"><img src="/images/empl/{{$IMGSRC}}" width="121" height="150" style="padding:1px;border:thin solid black;"> </div>
        </div>

        <font size="3">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    พนักงานผู้ทำผิดระเบียบวินัย
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <table style="width: 100%;" border="1">
                        <tbody>
                            <tr>
                                <td style="width: 30%;">
                                    ชื่อ - สกุล : <b>{{$emplname}}</b>
                                </td>
                                <td style="width: 35%;">
                                    รหัสพนักงาน : <b>{{$emplid}}</b>
                                </td>
                                <td style="width: 35%;">
                                    แผนก/ฝ่าย : <b>{{$department}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 33%;">
                                    ตำแหน่ง : <b>{{$position}}</b>
                                </td>
                                <td style="width: 33%;">
                                    วันที่กระทำผิด : <b>{{\Carbon\Carbon::parse($warning_date)->format('d/m/Y H:i')}}</b>
                                </td>
                                <td style="width: 33%;">
                                    วันที่ตักเตือน : <b>{{\Carbon\Carbon::parse($follow_date)->format('d/m/Y H:i')}}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </font>
        <font size="4">
            <div class="row" style="height: 100px">
                <div class="col-md-10 offset-md-1">

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    เมื่อวันที่ ..... <b>{{\Carbon\Carbon::parse($warning_date)->format('d/m/Y ..... เวลา ..... H : i')}}</b>..... ท่านได้กระทำการ <b>{{$corgroup_description}}</b> ซึ่งจากการกระทำดังกล่าวถือว่าได้ฝ่าฝืนต่อประกาศคำสั่งของบริษัท
                    และเป็นการกระทำผิดต่อระเบียบข้อบังคับเกี่ยวกับการทำงาน ของบริษัทในหมวดที่ 2 เรื่อง วินัยและโทษทางวินัย ในหัวข้อดังต่อไปนี้
                </div>
            </div>
        </font>
        <font size="3">
            {{-- <div class="row" style="height: 70px">
            <div class="col-md-10 offset-md-1">
            ซึ่งจากการกระทำดังกล่าวถือว่าได้ฝ่าฝืนต่อประกาศคำสั่งของบริษัท 
            และเป็นการกระทำผิดต่อระเบียบข้อบังคับเกี่ยวกับการทำงาน ของบริษัทในหมวดที่ 2 เรื่อง วินัยและโทษทางวินัย ในหัวข้อดังต่อไปนี้
            </div>
        </div> --}}
            <div class="row " style="height: 350px">
                <div class="col-md-9 offset-md-2 ">
                    <div id="cor" name="cor"></div>
                    <br><b>Remark : ...{{$remark}}... </b>
                </div>

            </div>
        </font>
        <font size="4">
            <div class="row" style="height: 40px">
                <div class="col-md-10 offset-md-1">
                    จากการกระทำความผิดดังกล่าวข้างต้นของท่านนั้น ท่านได้กระทำการเป็น
                </div>
            </div>
        </font>
        <font size="3">
            <div class="row" style="height: 40px">
                <div class="col-md-9 offset-md-2">
                    <b><i class="far fa-check-square"></i> ครั้งที่ : {{$penalty_qty}} </b>
                </div>
            </div>
        </font>
        <font size="4">
            <div class="row">
                <div class="col-md-10 offset-md-1" style="height: 30px">
                    บริษัทฯ จึงเห็นควรพิจารณาให้ดำเนินการลงโทษทางวินัยโดย
                </div>
            </div>
        </font>
        <font size="3">
            <div class="row">
                <div class="col-md-9 offset-md-2 ">
                    <div id='penalty'></div>
                </div>
            </div>
        </font>
        <br>
        <font size="4">
            <div class="row" style="height: 100px">
                <div class="col-md-10 offset-md-1">
                    พร้อมให้ท่านดำเนินการแก้ไขปรับปรุงตัวให้ดีขึ้นไม่กระทำความผิดระเบียบวินัยข้อบังคับบริษัทฯซ้ำอีก หากปรากฎภายหลังว่าการกระทำผิดต่อระเบียบ
                    วินัยข้อบังคับของบริษัทฯซ้ำอีก บริษัทฯ จะดำเนินการลงโทษทางวินัยตามที่กำหนดไว้ในข้อบังคับเกี่ยวกับการทำงานของบริษัทฯขั้นต่อไป
                </div>
            </div>
            <div class="row " style="height:110px">
                <div class="col-sm-1"></div>
                <div class="col-sm"> ลงชื่อ.............................................. พนักงานรับทราบและเข้าใจเป็นอย่างดี จะให้ความร่วมมือกับผู้บังคับบัญชาในการปรับปรุง</div>
            </div>
            <div class="row " style="height: 30px">
                <div class="col-sm-1"></div>
                <div class="col-sm"> ลงชื่อ.................................................................ผู้เตือน</div>
                <div class="col-sm"> ลงชื่อ.................................................................ผู้เตือน</div>
            </div>
            <div class="row " style="height: 30px">
                <div class="col-sm-1"></div>
                <div class="col-sm"> (...............................ผู้บังคับบัญชา...............................)</div>
                <div class="col-sm"> (....................ผู้จัดการฝ่าย/ผู้จัดการโรงงาน...................)</div>
            </div>
            <div class="row " style="height: 60px">
                <div class="col-sm-1"></div>
                <div class="col-sm"> วันที่..............................................................................</div>
                <div class="col-sm"> วันที่..............................................................................</div>
            </div>



            <div class="row " style="height: 30px">
                <div class="col-sm-1"></div>
                <div class="col-sm"> ลงชื่อ.................................................................ผู้เตือน</div>
                <div class="col-sm"> ลงชื่อ.................................................................ผู้เตือน</div>
            </div>
            <div class="row " style="height: 30px">
                <div class="col-sm-1"></div>
                <div class="col-sm"> (.......................เจ้าหน้าที่แรงงานสัมพันธ์.....................)</div>
                <div class="col-sm"> (..........ผู้จัดการฝ่าย HR / ผู้อำนวยการฝ่าย HR........)</div>
            </div>
            <div class="row " style="height: 30px">
                <div class="col-sm-1"></div>
                <div class="col-sm"> วันที่..............................................................................</div>
                <div class="col-sm"> วันที่..............................................................................</div>

            </div>
            <right>
                <p align='right'>วันที่หมดอายุ : <b>{{\Carbon\Carbon::parse($expiry_date)->format('d/m/Y')}}</b> </p>
            </right>
            <div class="row justify-content-center">
                <div id="reason"></div>
            </div>


    </div>
    </font>




</body>

</html>
<script>
    $(document).ready(function() {

        var test = '{{$company}}'
        switch (test) {
            case 'DSI':
                var companyreport = 'บริษัท ดีสโตน อินเตอร์เนชั่นแนล จำกัด'
                break;
            case 'SVO':
                var companyreport = 'บริษัท สวิซซ์-วัน คอร์ปอเรชั่น จำกัด'
                break;
            case 'DSL':
                var companyreport = 'บริษัท ดีสโตน จำกัด'
                break;
            case 'STR':
                var companyreport = 'บริษัท สยามทรัค เรเดียล จำกัด'
                break;
            case 'DRB':
                var companyreport = 'บริษัท ดีรับเบอร์ จำกัด'
                break;
            case 'DSC':
                var companyreport = 'บริษัท ดีสโตน คอร์ปอเรชั่น จำกัด'
                break;
            default:
                var companyreport = 'COMPANY2'
        }


        $('#reportcompany').html(companyreport);

        reason();






    });

    function reason() {
        //Reason
        $.ajax({
            type: 'GET',
            url: '/getdata/reason',
            data: {
                warning_no: '{{$warning_no}}'
            },
            dataType: "JSON",

            success: function(response) {
                if (response == 0) {
                    $('#reason').hide();
                    cor();
                } else {
                    console.log(response);
                    $('#reason').html('<h4><font color=red>ไม่เห็นชอบ : ..... ' + response + ' .....</font></h4><a href="/" class="btn btn-outline-danger btn-block">แก้ไขใบเตือน</a>');
                    cor();
                }

            }
        });

    }

    function cor() {
        //Cor     
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
                    var cordata = cordata + " <b> <i class='far fa-check-square'></i> " + response[i].cor_description + "<br>";

                }

                $('#cor').html(cordata);
                penalty();


            }
        });

    }

    function penalty() {
        //Penalty
        $.ajax({
            type: 'GET',
            url: '/getdata/penaltyid',
            data: {
                data: '{{$warning_no}}'
            },
            dataType: "JSON",

            success: function(response) {
                console.log(response);
                var penaltydata = "";
                var i;
                for (i = 0; i < response.length; i++) {
                    if (response[i].penalty_id != '3') {
                        $('#penalty').append(' <b> <i class="far fa-check-square"></i> ' + response[i].penalty_description + "<b><br> ");
                    } else {
                        $('#penalty').append(' <b> <i class="far fa-check-square"></i> ' + response[i].penalty_description + "<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       วันที่พักงานเริ่มต้น <b>.. {{\Carbon\Carbon::parse($penalty_start)->format('d/m/Y')}} .. </b>วันที่พักงานสิ้นสุด <b>.. {{\Carbon\Carbon::parse($penalty_end)->format('d/m/Y')}} ..</b> ");
                    }
                    // if (response[i].penalty_id == '1') {
                    //     $('#penalty').html(' <b> <i class="far fa-check-square"></i> '+response[i].penalty_description+"</b> ");
                    // } 
                    // if (response[i].penalty_id == '2') {
                    //     $('#penalty').html(' <b> <i class="far fa-check-square"></i> '+response[i].penalty_description+"</b> ");
                    // } 
                    // if (response[i].penalty_id == '3') {
                    //     $('#penalty3').html(' <b> <i class="far fa-check-square"></i> '+response[i].penalty_description+"</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       วันที่พักงานเริ่มต้น <b>.. {{\Carbon\Carbon::parse($penalty_start)->format('d/m/Y')}} .. </b>วันที่พักงานสิ้นสุด <b>.. {{\Carbon\Carbon::parse($penalty_end)->format('d/m/Y')}} ..</b> ");
                    // } 
                    // if (response[i].penalty_id == '4') {
                    //     $('#penalty4').html(' <b> <i class="far fa-check-square"></i> '+response[i].penalty_description+"</b> ");
                    // } 
                    // else {
                    //     penaltydata = penaltydata+' <b> <i class="far fa-check-square"></i> '+response[i].penalty_description+"</b>" ;
                    // }


                }
                setTimeout(function() {
                    window.print();
                }, 2000);

                // penaltydata = penaltydata+'<i class="far fa-square"></i> อื่นๆ  ................................................................................................................................................................................................';
                // $('#penalty').html(penaltydata);

                // document.execCommand('print', false, null);


            }
        });

    }
</script>