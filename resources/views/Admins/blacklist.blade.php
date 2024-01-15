@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h3>Blacklist Master</h3>
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
            <div id="bl_grid"></div>
        </div>
    </div>


<!-- Add Modal -->
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title text-white " id="exampleModalLabel">เพิ่ม Blacklist</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <label for="emplid">รหัสพนักงาน</label>
                <div class="row">
                    <div class="col-md-9">
                        <input id="emplid" class="form-control " readonly>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-danger" id="search"><i class="fas fa-search"></i> ค้นหา</button>
                    </div>
                </div>
                <br>
                <label for="cardid">เลขบัตรประชาชน</label><br>
                <input id="cardid" class="form-control" readonly>
                <br>
                <label for="bl_group">Blacklist Group</label><br>
                <div id="bl_group">sadf</div>
        </div>
        <div class="modal-footer">
                <button id="add_submit" type="button" class="btn btn-primary" data-dismiss="modal">ยืนยัน</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
        </div>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header bg-success ">
            <h5 class="modal-title text-white " id="exampleModalLabel">แก้ไข User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <label for="emplid_edit">รหัสพนักงาน</label>
                <div class="row">
                    <div class="col-md-9">
                        <input id="emplid_edit" class="form-control " disabled>
                        <input id="id_edit" class="form-control " hidden>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-danger" id="search_edit"><i class="fas fa-search"></i> ค้นหา</button>
                    </div>
                </div>
                <br>
                <label for="cardid_edit">เลขบัตรประชาชน</label><br>
                <input id="cardid_edit" class="form-control" disabled>
                <br>
                <label for="bl_group_edit">Blacklist Group</label><br>
                <div id="bl_group_edit">sadf</div>
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
function grid(){
    var source =
        {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'string'},
                { name: 'emplname', type: 'string'},
                { name: 'emplid', type: 'string'},
                { name: 'cardid', type: 'string'},
                { name: 'blacklist_group', type: 'string'},
                { name: 'blacklist_description', type: 'string'},
            ],
            url: '/admin/getblacklist/'
        };
        
        var dataAdapter = new $.jqx.dataAdapter(source);
        
        
    //Create Grid
    $("#bl_grid").jqxGrid(
        {
            width: 600,
            autoheight: true,
            source: dataAdapter,
            theme :'fresh',
            showfilterrow: true,
            filterable: true, 
            pageable: true,
            columns: [
            { text: 'รหัสพนักงาน', datafield: 'emplid', width: 100 },
            { text: 'ชื่อ - สกุล', datafield: 'emplname', width: 150 },
            { text: 'Card ID', datafield: 'cardid', width: 150 },
            { text: 'กลุ่ม', datafield: 'blacklist_description', width: 200 },
            ]
        });
}
function getrowdata(grid_name){
    var selectedrowindex = $(grid_name).jqxGrid('getselectedrowindex');
    var rowData = $(grid_name).jqxGrid('getrowdata', selectedrowindex);
    return  rowData
}
function emplgrid(){
    var source =
    {
        datatype: "json",
        datafields: [
            // { name: 'id', type: 'string'},
            { name: 'CODEMPID', type: 'string'},
            { name: 'CARDID', type: 'string'},
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
$('#search').on('click', function (event) {
    $('#search_modal').modal();
    emplgrid();
});

$('#search_edit').on('click', function (event) {
    $('#search_modal').modal();
    emplgrid();
});
$('#empl_grid').on('rowdoubleclick', function (event){
                    var rowData = getrowdata('#empl_grid');
                    $('#emplid').val(rowData.CODEMPID);
                    $('#cardid').val(rowData.CARDID);
                    $('#emplid_edit').val(rowData.CODEMPID);
                    $('#cardid_edit').val(rowData.CARDID);
                    $("#search_modal .close").click()
});
$("#add").click(function () {
    $('#add_modal').modal();
    blacklist_group_dropdown()
});

$('#add_submit').click(function () {
    if($('#emplid').val() == ""|$('#bl_group').val() == ""){
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
   
    
    }else{
        var blacklist_group = getdropdowndata('#bl_group');
        var emplid = $('#emplid').val();
        var cardid = $('#cardid').val();
        submitajax('addblacklist',null,emplid,cardid,blacklist_group);
    }
    
});
$('#edit_submit').click(function () {
    var blacklist_group = getdropdowndata('#bl_group_edit');
    var id = $('#id_edit').val();
    var emplid = $('#emplid_edit').val();
    var cardid = $('#cardid_edit').val();
        submitajax('editblacklist',id,emplid,cardid,blacklist_group); 
});

function getdropdowndata(grid_name){
    var items = $(grid_name).jqxDropDownList('getSelectedItem');
    var data = items.originalItem.blacklist_group
    return data;
}
function blacklist_group_dropdown(){
    var blsource =
    {
        datatype: "json",
        datafields: [
            { name: 'blacklist_group'},
            { name: 'blacklist_description'},                        
        ],
        url: '/getdata/blacklist_group',
        async : false
    };
    var bldata = new $.jqx.dataAdapter(blsource);
    $("#bl_group").jqxDropDownList({ 
        source: bldata,
        width: '100%', 
        height: '40',
        displayMember: 'blacklist_description',
        valueMember: 'blacklist_group',
    });
}

function blacklist_group_edit_dropdown(){
    var blsource =
    {
        datatype: "json",
        datafields: [
            { name: 'blacklist_group'},
            { name: 'blacklist_description'},                        
        ],
        url: '/getdata/blacklist_group',
        async : false
    };
    var bldata = new $.jqx.dataAdapter(blsource);
    $("#bl_group_edit").jqxDropDownList({ 
        source: bldata,
        width: '100%', 
        height: '40',
        displayMember: 'blacklist_description',
        valueMember: 'blacklist_group',
    });
    
}

function submitajax(mode,id,emplid,cardid,blacklist_group){
    $.ajax({
        type: 'POST',
        url: '/admin/'+mode,
        data : 
            {
                '_token' : '{{ csrf_token() }}',
                "id" : id,
                "emplid" : emplid ,
                "cardid" : cardid ,
                "blacklist_group" : blacklist_group,
            }, 
        dataType: "JSON", 
        success: function(response)    
            {
                console.log(response);
                if (response.result == true){
                    alert(response.message);
                    location.reload();
                }else{
                    alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.message);
                }  
            }
    });
}
$("#delete").click(function () {
    var rowData = getrowdata('#bl_grid');
    if (typeof rowData == "undefined"){
        alert('กรุณาเลือกรายการ');
    }else{
        var r = confirm('ยืนยันการลบ ?');
        if (r == true) {
            submitajax('deleteblacklist',rowData.id);
        }
    }     
});
$("#edit").click(function () {
    var rowData = getrowdata('#bl_grid');
      if (typeof rowData == "undefined")
      {
        alert('กรุณาเลือกรายการ');
      }
      else
      {
        $('#edit_modal').modal();
        $('#emplid_edit').val(rowData.emplid);
        $('#cardid_edit').val(rowData.cardid);
        $('#id_edit').val(rowData.id);
        blacklist_group_edit_dropdown();
        var index = $("#bl_group_edit").jqxDropDownList('getItemByValue', rowData.blacklist_group); 
        $("#bl_group_edit").jqxDropDownList({selectedIndex: index.visibleIndex }); 
      }      
});

$(document).ready(function() {
    grid();
});


</script>
@endsection