@extends('layouts.app')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h3>Email Master</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <button id="add" class="btn btn-primary"><i class="fas fa-plus-circle"></i> เพิ่ม </button> 
            <button id="edit" class="btn btn-success"><i class="fas fa-edit"></i> แก้ไข</button>
            <button id="delete" class="btn btn-danger"><i class="fas fa-trash"></i> ลบ</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div id="email_user_grid"></div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal " id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title text-white " id="exampleModalLabel">เพิ่ม Email</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <label >Type : </label>
            <input  type="radio" name="email_type" class="user_radio" value="USER"> Users
            <input type="radio" name="email_type" class="hr_radio" value="HR"> HR
            <br>
            <div id="division_grid">
                <label id="label" for="divisioncode"></label>
                <div id="divisioncode" ></div>
                <div id="hrcode" ></div>
            </div>
            <br>

            

            <div id="email_grid">
                <label for="email">Email</label><br>
                <div class="row">
                    <div class="col-8">
                        <input id="email" class="form-control" > 
                    </div>
                    <div class="col">
                        <button id="search" type="button" class="btn btn-outline-danger"><i class="fas fa-search"></i> ค้นหา </button>
                    </div>
                </div>
                
            </div>
            <br>
            <div id="level_grid">
                <label for="level">Level</label><br>
                <input id="level" type="number" class="form-control" > 
            </div>
        </div>
        <div class="modal-footer">
            <button id="add_submit" type="button" class="btn btn-primary" data-dismiss="modal">ยืนยัน</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
        </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal " id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header bg-success ">
            <h5 class="modal-title text-white " id="exampleModalLabel">แก้ไข Email</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            {{-- <label >Type : </label>
            <input  type="radio" name="email_type_edit" class="user_radio_edit" value="user"> Users
            <input type="radio" name="email_type_edit" class="hr_radio_edit" value="hr"> HR
            <br> --}}
            <div id="division_grid_edit">
                <label id="label_edit" for="divisioncode_edit"></label>
                <div id="divisioncode_edit" ></div>
                <div id="hrcode_edit" ></div>
            </div>
            <br>
            <div id="email_grid_edit">
                <label for="email_edit">Email</label><br>
                <input id="email_edit" class="form-control" disabled> 
            </div>
            <br>
            <div id="level_grid_edit">
                <label for="level_edit">Level</label><br>
                <input id="level_edit" type="number" class="form-control" > 
            </div>
        </div>
        <div class="modal-footer">
                <button id="edit_submit" type="button" class="btn btn-primary" data-dismiss="modal">ยืนยัน</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
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

$(document).ready(function() {
    emailgrid();
    DivisionData();
    HrData();
    // var divisiondata = DivisionData();
    // DivisionCode(divisiondata);
    $('#divisioncode').hide();
    $('#hrcode').hide();
    $('#email_grid').hide();
    $('#level_grid').hide();

    // $('#edit').off("click").on('click', function () {alert('กรุณาเลือกรายการ');});
    // $('#delete').off("click").on('click', function (event) { alert('กรุณาเลือกรายการ'); });
      
});

$('#search').on('click', function (event) {

$('#search_modal').modal();

//Prepareing Data
var source =
    {
        datatype: "json",
        datafields: [
            // { name: 'id', type: 'string'},
            { name: 'CODEMPID', type: 'string'},
            { name: 'emplname', type: 'string'},
            { name: 'EMAIL', type: 'string'},
            { name: 'DIVISIONNAME', type: 'string'},
            { name: 'DIVISIONCODE', type: 'int'},
            { name: 'POSITIONNAME', type: 'string'},
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

        columns: [
        // { text: 'ID', datafield: 'corgroup_id', width: 50 },
        { text: 'รหัสพนักงาน', datafield: 'CODEMPID', width: 80 },
        { text: 'ชื่อ - สกุล', datafield: 'emplname', width: 180 },
        { text: 'Division Code', datafield: 'DIVISIONCODE', width: 50 },
        { text: 'ฝ่าย', datafield: 'DIVISIONNAME', width: 120 },
        { text: 'EMAIL', datafield: 'EMAIL', width: 260 },
        ]
    });
$('#empl_grid').off("click").on('rowselect', function (event) 
    {
        var args = event.args;
        var rowData = args.row;
        $('#email').val(rowData.EMAIL);
        $("#search_modal .close").click()
    });
}); 

function row_selected(grid_name) {
    var selectedrowindex = $(grid_name).jqxGrid('getselectedrowindex');
    var rowData = $(grid_name).jqxGrid('getrowdata', selectedrowindex);
    return rowData;   
}

function editmodal(rowData){
    $('#edit_modal').modal();
    if(rowData.code == 'HR'){
        // HrData();
        $('#hrcode_edit').show();
        $('#divisioncode_edit').hide();
        var index = $('#hrcode_edit').jqxDropDownList('getItemByValue', rowData.name); 
        $('#hrcode_edit').jqxDropDownList({selectedIndex: index.visibleIndex }); 
        $('#label_edit').text('HR Company');
        codedata =  $('#hrcode_edit').val();
    }else{
        // DivisionData();
        $('#hrcode_edit').hide();
        $('#divisioncode_edit').show();
        var indexuser = $('#divisioncode_edit').jqxDropDownList('getItemByValue', rowData.code); 
        $('#divisioncode_edit').jqxDropDownList({selectedIndex: indexuser.visibleIndex }); 
        $('#label_edit').text('Division Code');
        codedata =  rowData.name;
    }
}

$('#edit').on('click', function () {
    rowData = row_selected('#email_user_grid');
    if (!rowData){
        alert('กรุณาเลือกรายการ');
    }else{
        editmodal(rowData);

        $('#divisioncode_edit').on('select', function (event)
        {
            codedata = event.args.item.originalItem.divisionname;

        });  

        $('#hrcode_edit').on('select', function (event)
        {
            codedata = event.args.item.originalItem.company;

        }); 
    
        $('#email_edit').val(rowData.email);
        $('#level_edit').val(rowData.level_approved);
    }
});

$('#add').off("click").on('click', function (event) {
    $('#add_modal').modal();
    $(".user_radio").click(function () {
        $('#divisioncode').show();
        $('#hrcode').hide();
        $('#label').text('Division Code');
        $('#email_grid').show();
        $('#level_grid').show();

    });

    $(".hr_radio").click(function () {
        $('#divisioncode').hide();
        $('#hrcode').show();
        $('#label').text('HR Company');
        $('#email_grid').show();
        $('#level_grid').show();

    });

    $('#divisioncode').on('select', function (event)
        {
            codedata = event.args.item.originalItem.divisionname;
        });                        
    
    $('#hrcode').on('select', function (event)
        {
            codedata = event.args.item.originalItem.company;
        });  
});

$('#add_submit').off("click").on('click', function (event) {
    if ($('input:radio[name=email_type]:checked').val() == 'USER'){
        var data = $('#divisioncode').val();
    }else{
        var data = $('#hrcode').val();
    }

    if(data == ''){
        alert('กรุณาใส่ข้อมูลให้ครบถ้วน');
        $('#email').val('');
        $('#level').val('');
        return 'STOP';
    }

    if($('#email').val() == ''){
        alert('กรุณาใส่ข้อมูลให้ครบถ้วน');
        $('#email').val('');
        $('#level').val('');
        return 'STOP';
    }

    if($('#level').val() == ''){
        alert('กรุณาใส่ข้อมูลให้ครบถ้วน');
        $('#email').val('');
        $('#level').val('');
        return 'STOP';
    }

    if(isEmail($('#email').val()) == false){
        alert('รูปแบบ Email ไม่ถูกต้อง');
        $('#email').val('');
        $('#level').val('');
        return 'STOP';
    }
  
    $.ajax({
        type: 'POST',
        url: '/admin/addemail',
        data : { 
                '_token' : '{{ csrf_token() }}',
                code : data ,
                name : codedata,
                type : $('input:radio[name=email_type]:checked').val(),
                email : $('#email').val(),
                level : $('#level').val()
            }, 
        dataType: "JSON", 
        success: function(response)    
            {   
                console.log(response);
                if (response.result == true){
                    alert('Success !');
                    location.reload();
                }else{
                    alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.message);
                    return "STOP";

                }  
            }
    }); 
    
});

$('#edit_submit').off("click").on('click', function (event) {
    if (rowData.code !== 'HR'){
        var data = $('#divisioncode_edit').val();
        var type = 'USER';
    }else{
        var data = $('#hrcode_edit').val();
        var type = 'HR';
    }
    if(data == ''){
        alert('กรุณาใส่ข้อมูลให้ครบถ้วน');
        return 'STOP';
    }
    if($('#email_edit').val() == ''){
        alert('กรุณาใส่ข้อมูลให้ครบถ้วน');
        return 'STOP';
    }

    if($('#level_edit').val() == ''){
        alert('กรุณาใส่ข้อมูลให้ครบถ้วน');
        return 'STOP';
    }

    if(isEmail($('#email_edit').val()) == false){
        alert('รูปแบบ Email ไม่ถูกต้อง');
        return 'STOP';
    }    
    console.log(rowData.id);
        $.ajax({
                    type: 'POST',
                    url: '/admin/editemail',
                    data : 
                        { 
                            '_token' : '{{ csrf_token() }}',
                            code : data ,
                            id : rowData.id,
                            name : codedata,
                            type : type,
                            email : $('#email_edit').val(),
                            level : $('#level_edit').val()
                        }, 
                    dataType: "JSON", 
                    success: function(response)    
                        {  
                            console.log(response);
                            if (response.result == true){
                                alert('Success !');
                                location.reload();


                            }else{
                                alert('เกิดข้อผิดพลาด \n'+response.message);
                            }

                        }
        }); 
}); 

$('#delete').off("click").on('click', function (event) {
    rowData = row_selected('#email_user_grid');
    // console.log(rowData);
    var r = confirm('ยืนยันการลบ \n'+rowData.email+'\nฝ่าย : '+rowData.name+'\nระดับ : '+rowData.level_approved);
    if (r == true) {
        $.ajax({
            type: 'POST',
            url: '/admin/deleteemail',
            data : 
                    {
                        '_token' : '{{ csrf_token() }}',
                        id : rowData.id ,
                        code : rowData.code
                    },
            dataType: "JSON", 
            success: function(response)    
                {  
                    if(response.result == true){
                        alert('Success !');
                        location.reload();
                    }else{
                        alert('เกิดข้อผิดพลาด\nสาเหตุ : '+response.message);
                    }
                    
                }
        });
    }
}); 

function DivisionData(){
    //Division Data 
    var divisionsource =
    {
        datatype: "json",
        datafields: [
            { name: 'divisioncode'},
            { name: 'divisionname'}, 
            { name: 'full'},
                                        
        ],
        url: '/getdata/division'
    };   
    var divisiondata = new $.jqx.dataAdapter(divisionsource);

    $('#divisioncode').jqxDropDownList({ 
                            
        source: divisiondata,
        searchMode: 'contains' ,
        width: '100%', 
        height: '40',
        displayMember: 'full',
        valueMember: 'divisioncode',
    });  
                    
    $('#divisioncode_edit').jqxDropDownList({ 
        source: divisiondata,
        searchMode: 'contains' ,
        width: '100%', 
        height: '40',
        displayMember: 'full',
        valueMember: 'divisioncode',
    });

}

function HrData(){
    //HR Data
    var hrsource = [{'company' : 'DSL'}
                    ,{'company' : 'DSP'}
                    ,{'company' : 'DRB'}
                    ,{'company' : 'DSI'}
                    ,{'company' : 'SVO'}
                    ,{'company' : 'STR'}];

    $('#hrcode').jqxDropDownList({ 
        source: hrsource,
        searchMode: 'contains' ,
        width: '100%', 
        height: '40',
        displayMember: 'company',
        valueMember: 'company',
    });

    $('#hrcode_edit').jqxDropDownList({ 
        
        source: hrsource,
        searchMode: 'contains' ,
        width: '100%', 
        height: '40',
        displayMember: 'company',
        valueMember: 'company',
    });
}                

function emailgrid(){
    
    //Prepareing Data
    var source =
            {
                datatype: "json",
                datafields: [
                    
                    { name: 'id', type: 'string'},
                    { name: 'code', type: 'string'},
                    { name: 'name', type: 'string'},
                    { name: 'email', type: 'string'},
                    { name: 'level_approved', type: 'string'},
                    { name: 'active', type: 'int'},
                    { name: 'company', type: 'string'},

                ],
                url: '/admin/getemail/',

                updaterow: function (rowid, rowdata, result) {     

                    updateactive(rowdata)
                    .done(function(data) {
                        
                        if (data.result == false) {
                            alert("เกิดข้อผิดพลาดในการ Active \n"+data.message);
                            $('#email_user_grid').jqxGrid('updatebounddata', 'cells');
                        } else {
                            $('#email_user_grid').jqxGrid('updatebounddata', 'cells');
                        }
                    });
                    result(true);
                }
            };
            
            var dataAdapter = new $.jqx.dataAdapter(source);
            
            
        //Create Grid
        $("#email_user_grid").jqxGrid(
            {
                width: 800,
                autoheight: true,
                source: dataAdapter,
                showfilterrow: true,
                filterable: true, 
                editable: true,
                columnsresize: true,
                pageable: true,
                theme :'fresh',
                 

                columns: [
                // { text: 'ID', datafield: 'corgroup_id', width: 50 },
                { text: 'Div.Code', datafield: 'code', width: 100,editable: false },
                { text: 'ฝ่าย', datafield: 'name', width: 200,editable: false },
                { text: 'บริษัท', datafield: 'company', width: 100,editable: false },
                { text: 'Email', datafield: 'email', width: 300,editable: false },
                { text: 'Level', datafield: 'level_approved', width: 50 ,editable: false},
                { text: 'Active', datafield: 'active',columntype: 'checkbox', width: 50 },
                ]
            });
}

function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
}

function updateactive(rowdata) {
    
    return $.ajax({
        url : '/admin/activeemail/',
        type : 'GET',
        dataType : 'json',
        data : {
            "id" : rowdata.id,
            "name" : rowdata.name,
            "level" : rowdata.level_approved,
            "code" : rowdata.code,
            "active" : rowdata.active
        }
    });
}
</script>

@endsection