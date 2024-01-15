<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/well.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    {{-- <script src="{{ asset('js/moment.js') }}"></script> --}}

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script> --}}

</head>
<body>
    
    <br>
    <div class="container ">
        <div class="col-lg-7 border border-dark">
            <div class="container "><br>
                <div class="row ">  
                    <h5>ใบเตือน : {{$warning_no}}</h5><br>
                </div>
                    <br>
                    <div class="row">
                            <table style="width: 500px;" border="0">
                                    <tbody>
                                        <tr><td style="width: 100px;">ชื่อ-สกุล </td>
                                            <td style="width: 400px;">: <b>{{$emplname}}</b> </td>
                                        </tr>
                                        <tr><td style="width: 100px;">รหัสพนักงาน </td>
                                            <td style="width: 400px;">: <b>{{$emplid}}</b> </td>
                                        </tr>
                                        <tr><td style="width: 100px;">แผนก/ฝ่าย  </td>
                                            <td style="width: 400px;">: <b>{{$department}}</b> </td>
                                        </tr>
                                        <tr><td style="width: 100px;">ตำแหน่ง :  </td>
                                            <td style="width: 400px;">: <b>{{$position}}</b> </td>
                                        </tr>
                                        <tr><td style="width: 100px;">วันที่กระทำผิด  </td>
                                            {{-- <td style="width: 400px;">: <b>{{$warning_date}}</b> </td> --}}
                                            <td style="width: 400px;">: <b>{{\Carbon\Carbon::parse($warning_date)->format('d / m / Y H:i')}}</b> </td>
                                        </tr>
                                        <tr><td style="width: 100px;">วันที่ตักเตือน  </td>
                                            {{-- <td style="width: 400px;">: <b>{{$follow_date}}</b> </td> --}}
                                            <td style="width: 400px;">: <b>{{\Carbon\Carbon::parse($follow_date)->format('d / m / Y H:i')}}</b> </td>
                                        </tr>

                                    </tbody>
                            </table>                
                     </div>
                    <br>
                    <div class="row">
                    ความผิด :  
                    </div>
                    <div class="row">
                        <div class="container" id="cor" name="cor"></div>
                    </div>
                    <br>
                    <div class="row">
                        ครั้งที่ : 
                    </div>
                    <div class="row">
                        <b><div class="container" id="penalty_qty" ></div></b>
                    </div>
                    <br>
                    <div class="row">
                            บทลงโทษ : 
                    </div>
                    <div class="row">
                            <div class="container" id="penalty" name="penalty"></div>
                    </div>
                    <br>
                </div>
            </div><br>
            <div class="col-lg-7 ">
                
                    <div class="row justify-content-center ">
                            
                            {{-- <div class="col-lg-3 "><a href="/email/approved/{{$warning_no}}/{{$email}}/{{$level}}/{{$type}}" class="btn btn-outline-primary btn-lg"> --}}
                            <div class="col-lg-3 ">    
                                <button id="approved-btn" class="btn btn-primary btn-lg"> {{$text}} </button>
                                
                            </div>  
                            <div class="col-lg-3"><button id="decline-btn" class="btn btn-danger btn-lg" > {{$text2}}</button></div>  
                    </div></div>
    
</div>


<!-- Modal -->
<div class="modal fade" id="decline" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header ">
              <h5 class="modal-title" id="exampleModalLabel">เหตุผลที่ไม่เห็นชอบ</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body justify-content-center">
                
                    ​<textarea id="remark" rows="3" cols="55" value=""></textarea>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="decline-submit" >ยืนยัน</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
          </div>
        </div>
      </div>





<script>
$(document).ready(function() {
    getcor();
    getpenalty();
});

$("#approved-btn").click(function () {
    $.ajax({
            type: 'GET',
            url: '/email/approved/',
            data : {
                    
                    remark : $('#remark').val(),
                    warning_no : '{{$warning_no}}',
                    email : '{{$email}}',
                    level : '{{$level}}',
                    type : '{{$type}}'
        
                },
            dataType: "JSON", 

            success: function(response)    
                {   
                    if (response.result == true){
                        alert(response.message);
                        window.close();
                    }else if (response.result == 'report'){
                        alert(response.message+"\n"+response.errorcode);
                        window.location ="/warns/report/{{$warning_no}}";
                    }else{
                        alert(response.message+"\nสาเหตุ : "+response.errorcode);
                    } 
                }
    });
});

$("#decline-btn").click(function () {
    $('#decline').modal();
});

$("#decline-submit").click(function () {
    var hasSpace = $('#remark').val();
    // console.log(hasSpace);
    if(hasSpace == 0){
        alert('เหตุผลห้ามเว้นว่าง');  
        return 'break'; 
    }

    if ($('#remark').val().length < 5){
        alert('กรุณาใส่เหตุผล 5 ตัวอักษรขึ้นไป');  
        return 'break';
    }
    else{

    // alert('ok');
    // return 'break';
            $.ajax({
            type: 'GET',
            url: '/email/decline',
            data : {
                    
                    remark : $('#remark').val(),
                    warning_no : '{{$warning_no}}',
                    email : '{{$email}}',
                    level : '{{$level}}',
                    type : '{{$type}}'
        
                },
            dataType: "JSON", 

            success: function(response)    
                {   
                if (response.result == true){
                    alert('ส่งคำร้องไม่เห็นชอบเรียบร้อย');
                    window.close();
                }else{
                    alert(response.message+"\nสาเหตุ : "+response.errorcode)
                } 
            }
        });   
    }
});

function getcor(){
    var corgroup_id;
    $.ajax({
            type: 'GET',
            url: '/getdata/corid',
            data : {
                data :'{{$warning_no}}'
                },
            dataType: "JSON", 

            success: function(response)    
                {   
                    
                    var cordata = "";
                    var i;
                    for (i=0; i < response.length ; i++) {
                        
                    var cordata = cordata+" <b> - "+response[i].cor_description+"<br>" ;
                    var corgroup_id = response[0].corgroup_id;
                    
                        $.ajax({   
                            type: "GET",
                            url: "/getdata/penaltyqty/{{$emplid}}/"+corgroup_id,
                            data: [{"emplid" : '{{$emplid}}'}, 
                                    {"corgroup_id" : corgroup_id }, 
                                    ],          
                            dataType: "JSON",               
                                success: function(response){       
                                    var integer = parseInt(response[0].penalty_qty, 10);
                                    $("#penalty_qty").html(integer +1); 
                                    
                                //        console.log(response[0].penalty_qty);
                                // var integer = parseInt(response[0].penalty_qty, 10);
                                
                                // $("#penalty_qty").val(integer +1); 
                                }
                        }); 
                    
                    }
                 
                    $('#cor').html(cordata);
            }
    }); 
    

}

// function getpenaltyqty(){
//         $.ajax({   
//             type: "GET",
//             url: "/getdata/penaltyqty/"+emplid+"/"+corgroup_id,
//             data: [{"emplid" : emplid}, 
//                     {"corgroup_id" : corgroup_id}, 
//                     ],          
//             dataType: "JSON",               
//                 success: function(response){          
//                 var integer = parseInt(response[0].penalty_qty, 10);
//                 $("#penalty_qty").val(integer +1); 
//                 }
        
//         });
// }

function getpenalty(){
    $.ajax({
            type: 'GET',
            url: '/getdata/penaltyid',
            data : {data :'{{$warning_no}}'},
            dataType: "JSON", 

            success: function(response)    
                {   
                    var penaltydata = "";
                    var i;
                    for (i=0; i < response.length ; i++) {
                        if (response[i].penalty_id == '3') {
                            penaltydata = penaltydata+' <b> - '+response[i].penalty_description+"</b> &nbsp;&nbsp;&nbsp;&nbsp;      วันที่พักงานเริ่มต้น <b> {{\Carbon\Carbon::parse($penalty_start)->format('d/m/Y')}}  </b>วันที่พักงานสิ้นสุด <b> {{\Carbon\Carbon::parse($penalty_end)->format('d/m/Y')}} </b> <br>";
                        } else {
                            penaltydata = penaltydata+' <b> - '+response[i].penalty_description+"</b><br>" ;
                        }
                        
                        
                    }
                    $('#penalty').html(penaltydata);
                }
    });   
}

</script>
</body>
</html>