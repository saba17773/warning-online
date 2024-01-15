@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-secondary  ">
            <div class="card-header bg-secondary border-secondary text-center "><font color=white size=4>แก้ไขใบเตือน </font> </div>
                    <div class="card-body">
                        
                        @csrf

                            <div class="form-group row">
                                <label for="warning_no" class="col-md-4 col-form-label text-md-right">Warning No.</label>      
                                <div class="col-md-6">    
                                <input id="warning_no"  name="warning_no"  class="form-control text-danger" value="{{$warning_no}}" required readonly >                    
                                </div>
                            </div>  

                            <div class="form-group row">
                                <label for="emplid" class="col-md-4 col-form-label text-md-right">รหัสพนักงาน</label>      
                                <div class="col-md-6">    
                                <input type="number" class="form-control" id="emplid"  name="emplid" value="{{$emplid}}" required  readonly autofocus >                      
                                </div>
                                {{-- ปิดการเปลี่ยนชื่อ --}}
                                {{-- <div class="col-sm">
                                    <button id="search" type="button" class="btn btn-outline-danger"><i class="fas fa-search"></i> ค้นหา </button>
                                </div> --}}
                            </div>

                            <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">ชื่อ - สกุล</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="name" value="{{$emplname}}" required  readonly>
                                    </div>
                            </div>

                            <div class="form-group row">
                                    <label for="postition" class="col-md-4 col-form-label text-md-right">ตำแหน่ง</label>
                                    <div class="col-md-6">
                                        <input id="position" class="form-control" name="position" value="{{$position}}" required readonly>
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="department" class="col-md-4 col-form-label text-md-right">แผนก</label>
                                    <div class="col-md-6">
                                        <input id="department" class="form-control" name="department"  value="{{$department}}"  required readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="division" class="col-md-4 col-form-label text-md-right">ฝ่าย</label>
                                    <div class="col-md-6">
                                        <input id="division" class="form-control" name="division"  value="{{$division}}"  required readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="company" class="col-md-4 col-form-label text-md-right">บริษัท</label>
                                    <div class="col-md-6">
                                        <input id="company"  class="form-control" name="company"  value="{{$company}}"  required readonly>
                                    </div>
                                </div>

                            <div class="form-group row">
                                    <label for="warning_date" class="col-md-4 col-form-label text-md-right">วันที่กระทำความผิด</label>
        
                                    <div class="col-md-6">
                                        <div id="warning_date"  name="warning_date" ></div>
                                    </div>
                            </div>

                            <div class="form-group row">
                                    <label for="follow_date" class="col-md-4 col-form-label text-md-right">วันที่ตักเตือน</label>
                                    <div class="col-md-6">
                                            <div id="follow_date"  name="follow_date"></div>
                                    </div>
                            </div>

                            <div class="form-group row" id="corgroup_grid">
                                    <label for="corgroup_id" class="col-md-4 col-form-label text-md-right">สาเหตุกระทำความผิด</label>
                                    <div class="col-md-6">                 
                                        <div id="corgroup_id" name="corgroup_id" value=""></div>                          
                                    </div>
                            </div>

                            <div class="form-group row" id="cor_grid">
                                    <label for="cor" class="col-md-4 col-form-label text-md-right">ระเบียบความผิด</label>
                                    <div class="col-md-6">                 
                                        <div id="cor" name="cor"></div>
                                        {{-- <input id="cordata" name="cordata" hidden>                        --}}
                                    </div>
                            </div>

                            <div class="form-group row" id="penalty_qty_grid">
                                <label for="penalty_qty" class="col-md-4 col-form-label text-md-right">ครั้งที่</label>
                                <div class="col-md-6">              
                                <input id="penalty_qty" class="form-control" name="penalty_qty" value="" required readonly>
                                    {{-- <div id="warning_qty" name="warning_qty">1</div>                           --}}
                                </div>
                        </div>

                        <div class="form-group row" id="penalty_grid">
                                <label for="penalty" class="col-md-4 col-form-label text-md-right">บทลงโทษ</label>
                                <div class="col-md-6">                 
                                    <div id="penalty" name="penalty"></div>
                                    {{-- <input id="penaltydata" name="penaltydata" hidden>                             --}}
                                </div>
                        </div>

                        <div class="form-group row" id="penalty_start_grid">
                                <label for="penalty_start" class="col-md-4 col-form-label text-md-right">วันพักงานเริ่มต้น</label>
    
                                <div class="col-md-6">
                                    <div id="penalty_start" class="form-control" name="penalty_start" ></div>
                                </div>
                        </div>

                        <div class="form-group row" id="penalty_end_grid">
                                <label for="penalty_end" class="col-md-4 col-form-label text-md-right">วันพักงานสิ้นสุด</label>
    
                                <div class="col-md-6">
                                    <div id="penalty_end" class="form-control" name="penalty_end"></div>
                                </div>
                        </div>

                        <div class="form-group row" id="remark_grid">
                            <label for="remark" class="col-md-4 col-form-label text-md-right">Remark</label>
                            <div class="col-md-6">              
                                    <input id="remark" class="form-control" name="remark" value="{{$remark}}">
                            </div>
                        </div>
                        
                        <input id="loginuser"  name="loginuser" value="{{ Auth::user()->userid }}" hidden>
                        <input id="divisioncode"  name="divisioncode" value="{{$divisioncode}}" hidden>
                        <input id="logincompany"  name="logincompany" value="{{ Auth::user()->company }}" hidden>
                        <br><hr noshade>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button id="go" name="go" class="btn btn-danger"><i class="far fa-edit"></i> บันทึก</button>
                                <a href="/home" class="btn btn-primary  float-right"><i class="fas fa-home"></i> กลับหน้าหลัก</a>
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
<script>
// $('#penalty_qty').val();

$('#search').on('click', function (event) {
    $('#search_modal').modal();
});
$('#search_modal').on('show.bs.modal', function () {
    emplgrid();
})
$('#empl_grid').on('rowdoubleclick', function (event){
                    var rowData = getrowdata('#empl_grid');
                    var corgroup_id = getdropdowndata('#corgroup_id')
                    var userid   = (rowData.EMAIL).substring(0, (rowData.EMAIL).lastIndexOf("@"));
                    console.log(rowData);
                    $('#userid').val(userid);
                    $('#emplid').val(rowData.CODEMPID);
                    $('#name').val(rowData.emplname);
                    $('#email').val(rowData.EMAIL);
                    $('#position').val(rowData.POSITIONNAME);
                    $('#department').val(rowData.DEPARTMENTNAME);
                    $('#company').val(rowData.COMPANYNAME);
                    $('#division').val(rowData.DIVISIONNAME);
                    $('#divisioncode').val(rowData.DIVISIONCODE);
                    $("#search_modal .close").click()
                    getpenaltyqty(rowData.CODEMPID,corgroup_id);
});
$('#corgroup_id').on('select', function (event) {
    var corgroup_id = getdropdowndata('#corgroup_id')
    var emplid = $('#emplid').val();
    getcor(corgroup_id);
    getpenaltyqty(emplid,corgroup_id);
});
$('#penalty').on('rowselect', function (event) {                    
    if (event.args.rowindex == 2) {
        $("#penalty_start").jqxDateTimeInput({ disabled: false,width: '100%',height: 40, formatString: 'dd-MMM-yyyy'  });
        $("#penalty_end").jqxDateTimeInput({ disabled: false,width: '100%',height: 40, formatString: 'dd-MMM-yyyy'  });
    }                  
});
$('#penalty').on('rowunselect', function (event) {                    
    if (event.args.rowindex == 2) {
        $('#penalty_start').jqxDateTimeInput({disabled: true,value: null ,});
        $('#penalty_end').jqxDateTimeInput({disabled: true,value: null });
    }
});

$("#go").on('click', function (e) {
    $('#go').attr("disabled", true);
    $('#go').html('<i class="fas fa-spinner fa-spin"></i> กำลังประมวลผล');

    var warning_value = $('#warning_date').jqxDateTimeInput('value');
    var follow_value = $('#follow_date').jqxDateTimeInput('value');
    var momentwarning = moment(warning_value).format('MM-D-YYYY HH:mm:ss');
    var momentfollow = moment(follow_value).format('MM-D-YYYY HH:mm:ss');

    if (warning_value > follow_value){
        alert('วันที่กระทำผิดต้องน้อยกว่าวันที่ตักเตือน');
        $('#go').attr("disabled", false);
        $('#go').html('<i class="far fa-edit"></i> บันทึก');
        return 'break';
    }
   
    var penalty_s_value = $('#penalty_start').jqxDateTimeInput('value');
    var penalty_e_value = $('#penalty_end').jqxDateTimeInput('value');               
    var momentpenaltystart = moment(penalty_s_value).format('MM-D-YYYY');
    var momentpenaltyend = moment(penalty_e_value).format('MM-D-YYYY');

    if (penalty_s_value){
        if(penalty_s_value > penalty_e_value){
            alert('วันพักงานเริ่มต้นต้องน้อยกว่าวันพักงานสิ้นสุด');
            $('#go').attr("disabled", false);
            $('#go').html('<i class="far fa-edit"></i> บันทึก');
            return 'break';
        }
        if( follow_value > penalty_s_value){
            alert('วันที่ตักเตือนต้องน้อยกว่าวันพักงานเริ่มต้น')
            $('#go').attr("disabled", false);
            $('#go').html('<i class="far fa-edit"></i> บันทึก');
            return 'break';
        }   
    }

    
    // Get all selected records.
    var corgroup_id = getdropdowndata('#corgroup_id')
    var rows = $("#cor").jqxGrid('selectedrowindexes');
    var selectedRecords = new Array();
    for (var m = 0; m < rows.length; m++) {
        var row = $("#cor").jqxGrid('getrowdata', rows[m]);
        selectedRecords[selectedRecords.length] = row["cor_id"];    
    }
 
    var cor_id = selectedRecords;

    var rows = $("#penalty").jqxGrid('selectedrowindexes');
    var selectedRecords2 = new Array();
    for (var m = 0; m < rows.length; m++) {
        var row = $("#penalty").jqxGrid('getrowdata', rows[m]);
        selectedRecords2[selectedRecords2.length] = row["Penalty_ID"];
    }
    var penalty_id = selectedRecords2;

    // Check if ระเบียบความผิด is selected ?

    if (cor_id == ""){
        alert('กรุณาเลือกระเบียบความผิด'); 
        $('#go').attr("disabled", false);
        $('#go').html('<i class="far fa-edit"></i> บันทึก');
        return "nope"; //STOP 
        }
    // Check if บทลงโทษ is selected ?
    if (penalty_id == ""){
        alert('กรุณาเลือกบทลงโทษ'); 
        $('#go').attr("disabled", false);
        $('#go').html('<i class="far fa-edit"></i> บันทึก');
        return "nope"; //STOP 
    }

    
    //UPDATE fuction
 
    $.ajax({
    type: 'POST',
    url: '/warns/edit',
    data : {
        '_token' : '{{ csrf_token() }}',
        warning_no : '{{$warning_no}}',
        emplid : $('#emplid').val(),
        warning_date : momentwarning,
        follow_date : momentfollow,
        corgroup_id : corgroup_id,
        cor_id :cor_id,
        penalty_id : penalty_id,
        penalty_start : momentpenaltystart,
        penalty_end : momentpenaltyend,
        penalty_qty : $('#penalty_qty').val(),
        department : $('#department').val(),
        division : $('#division').val(),
        divisioncode :$('#divisioncode').val(),
        company : $('#company').val(),
        loginuser : "{{ Auth::user()->userid }}",
        remark : $('#remark').val(),
    },

    success: function(response)    
        {      
            if (response.result == true){
                alert('{{$warning_no}}\nแก้ไขใบเตือนได้สำเร็จ !');
                        window.location = "/warns/email/{{$warning_no}}";
            }else{
                alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.data );
                $('#go').attr("disabled", false);
                $('#go').html('<i class="far fa-edit"></i> บันทึก');
            }                    
        },
    }); 
});
function prepareing(){
    var warndate = moment('{{$warning_date}}').format('MM-D-YYYY HH:mm:ss');
    var followdate = moment('{{$follow_date}}').format('MM-D-YYYY HH:mm:ss');
    corgroup_dropdown();
    penalty();
    $("#warning_date").jqxDateTimeInput({ value: warndate ,width: '100%',height: 40,showTimeButton: true, formatString: 'dd/MM/yyyy HH:mm'  });
    $("#follow_date").jqxDateTimeInput({ value: followdate ,showTimeButton: true,width: '100%',height: 40, formatString: 'dd/MM/yyyy HH:mm'  });
        
    $('#penalty').on('rowselect', function (event) {                    
        if (event.args.rowindex == 2) {
            $("#penalty_start").jqxDateTimeInput({ disabled: false,width: '100%',height: 40,value: new Date() , formatString: 'dd/MM/yyyy'  });
            $("#penalty_end").jqxDateTimeInput({ disabled: false,width: '100%',height: 40,value: new Date() , formatString: 'dd/MM/yyyy'  });
        }                  
    });
    $('#penalty').on('rowunselect', function (event) {                    
        if (event.args.rowindex == 2) {
            $('#penalty_start').jqxDateTimeInput({disabled: true,value: null ,});
            $('#penalty_end').jqxDateTimeInput({disabled: true,value: null });
        }
    });

    if ('{{$penalty_start}}'){
        $("#penalty_start").jqxDateTimeInput({ value: "{{$penalty_start}}" ,width: '100%',height: 40, formatString: 'dd/MM/yyyy'  });
        $("#penalty_end").jqxDateTimeInput({ value: "{{$penalty_end}}" ,width: '100%',height: 40, formatString: 'dd/MM/yyyy'  }); 
    }else{
        $("#penalty_start").jqxDateTimeInput({ disabled: true , value: null ,width: '100%',height: 40, formatString: 'dd/MM/yyyy'  });
        $("#penalty_end").jqxDateTimeInput({ disabled: true , value: null ,width: '100%',height: 40, formatString: 'dd/MM/yyyy'  }); 
    }   
}
function emplgrid(){
    var source =
    {
        datatype: "json",
        datafields: [
            // { name: 'id', type: 'string'},
            { name: 'CODEMPID', type: 'string'},
            { name: 'emplname', type: 'string'},
            { name: 'EMAIL', type: 'string'},
            { name: 'DIVISIONNAME', type: 'string'},
            { name: 'DIVISIONCODE', type: 'string'},
            { name: 'POSITIONNAME', type: 'string'},
            { name: 'DEPARTMENTNAME', type: 'string'},
            { name: 'COMPANYNAME', type: 'string'},
        ],
        url: '/getdata/empl/'
    };
    
    var dataAdapter = new $.jqx.dataAdapter(source);
    $("#empl_grid").jqxGrid(
        {
            width: 760,
            autoheight: true,
            source: dataAdapter,
            theme :'fresh',
            showfilterrow: true,
            filterable: true, 
            pageable: true,

            columns: [
            { text: 'รหัสพนักงาน', datafield: 'CODEMPID', width: 80 },
            { text: 'ชื่อ - สกุล', datafield: 'emplname', width: 180 },
            { text: 'ตำแหน่ง', datafield: 'DEPARTMENTNAME', width: 120 },
            { text: 'ฝ่าย', datafield: 'DIVISIONNAME', width: 120 },
            { text: 'บริษัท', datafield: 'COMPANYNAME', width: 60 },
            ]
        });
}
function getrowdata(grid_name){
    var selectedrowindex = $(grid_name).jqxGrid('getselectedrowindex');
    var rowData = $(grid_name).jqxGrid('getrowdata', selectedrowindex);
    return  rowData
}
function getdropdowndata(dropdown_name){
    var items = $(dropdown_name).jqxDropDownList('getSelectedItem');
    var corgroup_id = items.originalItem.corgroup_id
    return corgroup_id;
}
function corgroup_dropdown(){
    var corgroupsource =
    {
        datatype: "json",
        datafields: [
            { name: 'corgroup_id'},
            { name: 'corgroup_description'},                        
        ],
        url: '/getdata/corgroup',
        async : false
    };
    var corgroupdata = new $.jqx.dataAdapter(corgroupsource);
    $("#corgroup_id").jqxDropDownList({ 
        source: corgroupdata,
        width: '100%', 
        height: '40',
        displayMember: 'corgroup_description',
        valueMember: 'corgroup_id',
    });
    
    var index = $("#corgroup_id").jqxDropDownList('getItemByValue', '{{$corgroup_id}}'); 
    $("#corgroup_id").jqxDropDownList({selectedIndex: index.visibleIndex }); 
    
}
function getpenaltyqty(emplid,corgroup_id){
    var oldcorgroup = '{{$corgroup_id}}';
    $.ajax({   
        type: "GET",
        url: "/getdata/penaltyqty/"+emplid+"/"+corgroup_id,
        data: [{"emplid" : emplid}, 
                {"corgroup_id" : corgroup_id}, 
                ],          
        dataType: "JSON",               
            success: function(response){          
            var integer = parseInt(response[0].penalty_qty, 10);
            // console.log(integer+1);
            if(integer > 0){
                    $("#penalty_qty").val(integer+1); 
                }else{
                    $("#penalty_qty").val(1); 
                }
            }
    
    });
}
function getcor(corgroup_id){
    var corsource =
        {
            datatype: "json",
            datafields: [
                { name: 'cor_id'},
                { name: 'cor_description'},                        
            ],
            url: '/getdata/cor/'+corgroup_id
        };
    var cordata = new $.jqx.dataAdapter(corsource);
    $("#cor").jqxGrid(
    {
        width: '100%',
        source: cordata,
        selectionmode: 'checkbox',
        showheader: false,
        autorowheight: true,
        autoheight: true,
        altrows: true,
        columns: [
        { text: 'DESC', datafield: 'cor_description', width: '91%' },
        ]
    }); 

    $.ajax({
        type: 'GET',
        url: '/getdata/corid',
        data : {data : '{{$warning_no}}'},
        dataType: "JSON", 

        success: function(response)    
            {   
                if ('{{$corgroup_id}}' != corgroup_id){
                    $("#cor").jqxGrid('clearSelection');
                }else{
                    response.forEach(function(response){
                    $('#cor').jqxGrid('selectrow', (response.cor_id-1));
                    });
                }   
                
                if({{ Auth::user()->level }} != 2){
                $("#cor").jqxGrid({ disabled: true }); 
                }
            }
    }); 
        
}
function penalty(){
    var penaltysource =
    {
        datatype: "json",
        datafields: [
            { name: 'Penalty_ID'},
            { name: 'Penalty_Description'},                        
        ],
        url: '/getdata/penalty'
    };
    
    var penaltydata = new $.jqx.dataAdapter(penaltysource);

    $("#penalty").jqxGrid(
    {
        width: '100%',
        source: penaltydata,
        selectionmode: 'checkbox',
        showheader: false,
        autorowheight: true,
        autoheight: true,
        altrows: true,
        columns: [
        { text: 'เลือกทั้งหมด', datafield: 'Penalty_Description', width: '91%' },
        ]
    });

    $.ajax({
        type: 'GET',
        url: '/getdata/penaltyid',
        data : {data : '{{$warning_no}}'},
        dataType: "JSON", 

        success: function(response)    
            {
                response.forEach(function(response){
                    $('#penalty').jqxGrid('selectrow', (response.penalty_id-1));
                });
                    if({{ Auth::user()->level }} != 2){
                    $("#penalty").jqxGrid({ disabled: true }); 
                    }
            }
        });
}

$(document).ready(function () { 
        
        prepareing();
        var corgroup_id = getdropdowndata('#corgroup_id')
        var emplid = $('#emplid').val();
        getfirstpenaltyqty(emplid,corgroup_id);

        if({{ Auth::user()->level }} != 2){
            $("#corgroup_id").jqxDropDownList({ disabled: true }); 
        }

});

function getfirstpenaltyqty(emplid,corgroup_id){
        $.ajax({   
            type: "GET",
            url: "/getdata/penaltyqty/"+emplid+"/"+corgroup_id,
            data: [{"emplid" : emplid}, 
                    {"corgroup_id" : corgroup_id}, 
                    ],          
            dataType: "JSON",               
                success: function(response){       

                var integer = parseInt(response[0].penalty_qty, 10);
                // console.log(integer);  
                if(integer){
                    $("#penalty_qty").val(integer+1); 
                }else{
                    $("#penalty_qty").val(1); 
                }
                
                }
        
        });
    }

   
</script>

@endsection