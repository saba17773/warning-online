@extends('layouts.app')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h3>Corgroup Master</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <button id="add" class="btn btn-primary"><i class="fas fa-plus-circle"></i> เพิ่ม </button> 
            <button id="edit" class="btn btn-success"><i class="fas fa-edit"></i> แก้ไข</button>
            {{-- <button id="delete" class="btn btn-danger"><i class="fas fa-trash"></i> ลบ</button> --}}
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div id="corgroup_grid"></div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title text-white " id="exampleModalLabel">เพิ่ม Corgroup</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <label for="corgroup_desc">Description</label>
            <input id="corgroup_desc" class="form-control">
            <br>
            <label for="cutoff">Counting Cutoff</label><br>
            <input type="radio" name="cutoff" value="0" /> By Month
            <input type="radio" name="cutoff" value="1" /> By Corgroup
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
            <h5 class="modal-title text-white " id="exampleModalLabel">เพิ่ม Corgroup</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <label for="corgroup_desc_edit">Description</label>
            <input id="corgroup_desc_edit" class="form-control" >
            <br>
            <label for="cutoff_edit">Counting Cutoff</label><br>
            <input type="radio" name="cutoff_edit" value="0" /> By Month
            <input type="radio" name="cutoff_edit" value="1" /> By Corgroup
        </div>
        <div class="modal-footer">
                <button id="edit_submit" type="button" class="btn btn-primary" data-dismiss="modal">ยืนยัน</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
        </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    corgroupgrid();
});
$('#delete').off("click").on('click', function (event) { alert('กรุณาเลือกรายการ'); });
$('#edit').off("click").on('click', function () {alert('กรุณาเลือกรายการ');});


$('#add').off("click").on('click', function (event) {
    $('#add_modal').modal();
        $('#add_submit').off("click").on('click', function (event) {
            var cutoff = $('input:radio[name=cutoff]:checked').val();
            if (cutoff === undefined | $('#corgroup_desc').val() === ""){
                alert('กรุณาใส่ข้อมูลให้ครบถ้วน');
                return 'STOP';         
            }else{           
                $.ajax({
                        type: 'GET',
                        url: '/admin/addcorgroup/',
                        data : 
                                    {"corgroup_desc" : $('#corgroup_desc').val() ,
                                    "cutoff" : cutoff}
                                    , 
                        dataType: "JSON", 
                        success: function(response)    
                            {   
                                if (response.result == false){
                                    alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.message);
                                        return "STOP";

                                }  
                                alert('Success !');
                                location.reload();
                            }
                }); 
            }
        });
});   






$('#corgroup_grid').on('rowselect', function (event) 
    {
        var args = event.args;
        var rowBoundIndex = args.rowindex;
        var rowData = args.row;
        var cutoffedit

        console.log(rowData.active)
        $('#edit').off("click").on('click', function (event) {
            
            
            $('#edit_modal').modal();
            $('#corgroup_desc_edit').val(rowData.corgroup_description);
            $("input:radio[name=cutoff_edit][value="+rowData.cutoff+"]").prop( 'checked', true );
            
            
        });

        $('#edit_submit').off("click").on('click', function (event) {
            
            cutoffedit = $('input:radio[name=cutoff_edit]:checked').val();
            if (cutoffedit === undefined | $('#corgroup_desc_edit').val() === ""){
                alert('กรุณาใส่ข้อมูลให้ครบถ้วน');
                return 'STOP';         
            }else{   
                $.ajax({
                            type: 'GET',
                            url: '/admin/editcorgroup/',
                            data : 
                                    {
                                        "corgroup_id" : rowData.corgroup_id ,
                                        "corgroup_desc" : $('#corgroup_desc_edit').val() ,
                                        "cutoff" : cutoffedit}
                                        , 
                            dataType: "JSON", 
                            success: function(response)    
                                {  
                                    if (response.result == false){
                                        alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.message);
                                        return "STOP";

                                    }
                                    alert('Success !');
                                    location.reload();
                                }
                }); 
            }
        }); 

        $('#delete').off("click").on('click', function (event) {

        if(rowData.active == 1){
            alert('Corgroup นี้ถูกใช้งานอยู่ \nไม่สามารถลบได้');
            return "STOP";
        }
        var r = confirm('ยืนยันการลบ ? \n'+rowData.corgroup_description);
        if (r == true) {
            $.ajax({
                        type: 'GET',
                        url: '/admin/deletecorgroup/',
                        data : {"corgroup_id" : rowData.corgroup_id ,},
                        dataType: "JSON", 
                        success: function(response)    
                            {  
                                if (response.result == false){
                                    alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.message);
                                    return "STOP";
                                }
                                alert('Success !');
                                location.reload();
                            }
                    });
        }
        }); 
});


function corgroupgrid(){
    var source =
            {
                datatype: "json",
                datafields: [
                    
                    // { name: 'id', type: 'string'},
                    { name: 'corgroup_id', type: 'string'},
                    { name: 'corgroup_description', type: 'string'},
                    { name: 'cutoff', type: 'string'},
                    { name: 'cutoff_desc', type: 'string'},
                    { name: 'active', type: 'int'},

                ],
                url: '/admin/getcorgroup/',

                updaterow: function (rowid, rowdata, result) {              
                    updateactive(rowdata)
            		.done(function(data) {
            			if (data.result == false) {
            				alert("เกิดข้อผิดพลาดในการ Active \n"+data.message);
            			} else {
            				$('#corgroup_grid').jqxGrid('updatebounddata', 'cells');
            			}
            		});
                    result(true);
                }

            };
            
            var dataAdapter = new $.jqx.dataAdapter(source);
            
            
        //Create Grid
        $("#corgroup_grid").jqxGrid(
            {
                width: 650,
                autoheight: true,
                editable: true,
                source: dataAdapter,
                theme :'fresh',
                columns: [
                // { text: 'ID', datafield: 'corgroup_id', width: 50 },
                { text: 'Corgroup', datafield: 'corgroup_description', width: 500 ,editable: false},
                { text: 'Cutoff', datafield: 'cutoff_desc', width: 100 , editable: false },
                { text: 'Active', datafield: 'active', width: 50 ,columntype: 'checkbox',}
                ]
            });
}

function updateactive(rowdata) {
    return $.ajax({
        url : '/admin/activecorgroup/',
        type : 'GET',
        dataType : 'json',
        data : {
            "corgroup_id" : rowdata.corgroup_id,
            "active" : rowdata.active
        }
    });
}
</script>

@endsection