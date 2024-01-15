@extends('layouts.app')

@section('content')
<style type="text/css">
  .jqx-grid-column-header {
    font-weight: bold;
  }

  .green {
    color: black\9;
    background-color: green;
  }

  .yellow {
    color: black\9;
    background-color: yellow\9;
  }

  .red {
    color: black\9;
    background-color: #e83636\9;
  }

  .green:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected),
  .jqx-widget .green:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
    color: black;
    background-color: #00cc00;
  }

  .yellow:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected),
  .jqx-widget .yellow:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
    color: black;
    background-color: yellow;
  }

  .red:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected),
  .jqx-widget .red:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
    color: black;
    background-color: #e83636;
  }
</style>

<div class="container-fluid">
  <div>
    <a href='/warns/create' class="btn btn-primary  "><i class="fas fa-plus-circle"></i> สร้างใบเตือน</a>
    <button id="email" name="email" class="btn btn-success "><i class="fas fa-file-alt"></i> รายละเอียดใบเตือน</button>
    <button id="edit" name="edit" class="btn btn-secondary"><i class="fas fa-edit"></i> แก้ไข</button>
    <button id="cancel" name="cancel" class="btn btn-primary  "><i class="fas fa-strikethrough"></i> ยกเลิกใบเตือน</button>
    <button id="print" name="print" class="btn btn-danger "><i class="fas fa-print"></i> พิมพ์</button>

    {{-- <button id="print2" name="print2" class="btn btn-danger float-right "><i class="fas fa-print"></i> พิมพ์(ทดสอบ)</button> --}}

  </div>
  <br>
  <div id="grid"></div>
</div>

<!-- Modal -->
<div class="modal " id="cor_id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger ">
        <h5 class="modal-title text-white " id="exampleModalLabel">ระเบียบความผิด</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="cordata"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>

<div class="modal " id="penalty_id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title text-white " id="exampleModalLabel">บทลงโทษ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="penaltydata"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    grid();


    $("#edit").click(function() {
      var rowData = getgrid('#grid');
      if (typeof rowData == "undefined") {
        alert('กรุณาเลือกรายการ');
      } else if (rowData.description == "อนุมัติเรียบร้อย" || rowData.description == "ยกเลิก") {
        alert('ใบเตือนนี้ได้ดำเนินการเสร็จสิ้นแล้ว ไม่สามารถแก้ไขได้');

      } else {
        window.location = '/getdata/edit/' + rowData.warning_no;
      }
    });

    $("#email").click(function() {
      rowData = getgrid('#grid');
      if (typeof rowData == "undefined") {
        alert('กรุณาเลือกรายการ');
      } else {
        window.location = '/warns/email/' + rowData.warning_no;
      }
    });

    $("#print").click(function() {
      rowData = getgrid('#grid');

      if (typeof rowData == "undefined") {
        alert('กรุณาเลือกรายการ');
      } else if (rowData.description == "ยกเลิก" | rowData.description == "อนุมัติเรียบร้อย") {
        var newWindow = window.open('/warns/report/' + rowData.warning_no, 'REPORT', 'resizable,scrollbars');

      } else {
        alert('ใบเตือนนี้ยังไม่เสร็จสิ้นขั้นตอนการอนุมัติ');
        // newWindow.print();
      }
    });

    $("#cancel").click(function() {
      rowData = getgrid('#grid');
      typeCheck = '{{Auth::user()->level}}';
      Namecheck = '{{Auth::user()->userid}}';

      if (typeof rowData == "undefined") {
        alert('กรุณาเลือกรายการ');
      } else
      if (confirm('Are you sure you?')) {
        if (typeCheck == 2) {
          // alert(rowData.warning_no);

          submitajax('Cancelcase', rowData.warning_no, Namecheck);
          // alert(rowData.warning_no);



        } else {
          if (rowData.created_by == Namecheck | rowData.description == "รอการพิจารณา") {
            alert("Cancel สำหรับ User");

          } else {
            alert('ไม่มีสิทธิยกเลิกรายการนี้');
            // newWindow.print();
          }
        }
      } else {
        // Do nothing!
        //console.log('Thing was not saved to the database.');
      }

    });



    $("#print2").click(function() {
      rowData = getgrid('#grid');

      if (typeof rowData == "undefined") {
        alert('กรุณาเลือกรายการ');
      } else if (rowData.description == "ยกเลิก" | rowData.description == "อนุมัติเรียบร้อย") {
        var newWindow = window.open('/warns/report2/' + rowData.warning_no, 'REPORT', 'resizable,scrollbars');

      } else {
        alert('ใบเตือนนี้ยังไม่เสร็จสิ้นขั้นตอนการอนุมัติ');
        // newWindow.print();
      }
    });


  });



  function getgrid(grid_name) {
    var selectedrowindex = $(grid_name).jqxGrid('getselectedrowindex');
    var rowData = $(grid_name).jqxGrid('getrowdata', selectedrowindex);
    return rowData
  }

  function grid() {

    console.log('{{Auth::user()->company}}');
    var source = {
      datatype: "json",
      datafields: [

        // { name: 'id', type: 'string'},
        {
          name: 'warning_no',
          type: 'string'
        },
        {
          name: 'warning_date',
          type: 'date'
        },
        {
          name: 'follow_date',
          type: 'date'
        },
        {
          name: 'truewarndate',
          type: 'string'
        },
        {
          name: 'truefollowdate',
          type: 'string'
        },
        {
          name: 'emplid',
          type: 'string'
        },
        {
          name: 'emplname',
          type: 'string'
        },
        {
          name: 'position',
          type: 'string'
        },
        {
          name: 'company',
          type: 'string'
        },
        {
          name: 'department',
          type: 'string'
        },
        {
          name: 'division',
          type: 'string'
        },
        {
          name: 'divisioncode',
          type: 'string'
        },
        {
          name: 'corgroup_description',
          type: 'string'
        },
        {
          name: 'penalty_qty',
          type: 'string'
        },
        {
          name: 'company',
          type: 'string'
        },
        {
          name: 'remark',
          type: 'string'
        },
        {
          name: 'penalty_start',
          type: 'date'
        },
        {
          name: 'penalty_end',
          type: 'date'
        },
        {
          name: 'description',
          type: 'string'
        },
        {
          name: 'expiry_date',
          type: 'date'
        },
        {
          name: 'created_by',
          type: 'string'
        },
        {
          name: 'REJECTREMARK',
          type: 'string'
        },

      ],
      url: '/getdata/warning'
    };

    var dataAdapter = new $.jqx.dataAdapter(source);


    //Create Grid
    $("#grid").jqxGrid({
      width: '100%',
      // height: '100%',
      source: dataAdapter,
      showfilterrow: true,
      filterable: true,
      columnsresize: true,
      pageable: true,
      theme: 'fresh',


      columns: [

        // { text: 'ID', datafield: 'divisioncode', width: 70 },
        {
          text: 'สถานะ',
          datafield: 'description',
          width: 90,
          cellclassname: function(row) {
            var dataRecord = $("#grid").jqxGrid('getrowdata', row);

            if (dataRecord.description == "ตรวจสอบเรียบร้อย") {
              return 'yellow';
            }
            if (dataRecord.description == "ยกเลิก") {
              return 'red';
            }
            if (dataRecord.description == "อนุมัติเรียบร้อย") {
              return 'green';
            }
          }
        },
        {
          text: 'Warning No.',
          datafield: 'warning_no',
          width: 130
        },
        {
          text: 'วันที่กระทำผิด',
          datafield: 'warning_date',
          cellsformat: 'dd/MM/yyyy (HH:mm)',
          width: 150
        },
        {
          text: 'วันที่ตักเตือน',
          datafield: 'follow_date',
          cellsformat: 'dd/MM/yyyy (HH:mm)',
          width: 150
        },
        {
          text: 'รหัสพนักงาน',
          datafield: 'emplid',
          width: 90
        },
        {
          text: 'ชื่อ-สกุล',
          datafield: 'emplname',
          width: 150
        },
        {
          text: 'ตำแหน่ง',
          datafield: 'position',
          width: 150
        },
        {
          text: 'ฝ่าย',
          datafield: 'division',
          width: 100
        },
        {
          text: 'แผนก',
          datafield: 'department',
          width: 150
        },
        {
          text: 'สาเหตุ',
          datafield: 'corgroup_description',
          width: 130
        },

        {
          text: 'ความผิด',
          width: 65,
          columntype: 'button',
          cellsrenderer: function() {
            return "Click";
          },
          buttonclick: function(row) {

            var dataRecord = $("#grid").jqxGrid('getrowdata', row);

            $.ajax({
              type: 'GET',
              url: '/getdata/corid',
              data: {
                data: dataRecord.warning_no
              },
              dataType: "JSON",

              success: function(response) {
                var cordata = "";
                var i;
                for (i = 0; i < response.length; i++) {

                  var cordata = cordata + (i + 1) + ".) " + response[i].cor_description + "<br>";

                }

                $('#cordata').html(cordata);
                $('#cor_id').modal();

              }
            });
          }
        },

        {
          text: 'ครั้งที่',
          datafield: 'penalty_qty',
          width: 40
        },
        {
          text: 'บทลงโทษ',
          width: 80,
          columntype: 'button',
          cellsrenderer: function() {
            return "Click";
          },
          buttonclick: function(row) {

            var dataRecord = $("#grid").jqxGrid('getrowdata', row);

            $.ajax({
              type: 'GET',
              url: '/getdata/penaltyid',
              data: {
                data: dataRecord.warning_no
              },
              dataType: "JSON",

              success: function(response) {
                var penaltydata = "";
                var i;
                for (i = 0; i < response.length; i++) {

                  penaltydata = penaltydata + '<i class="far fa-check-square"></i> ' + response[i].penalty_description + "<br>";

                }

                $('#penaltydata').html(penaltydata);
                $('#penalty_id').modal();

              }
            });
          }
        },


        {
          text: 'วันพักงานเริ่มต้น',
          datafield: 'penalty_start',
          filtertype: 'date',
          cellsformat: 'dd/MM/yyyy',
          width: 130
        },
        {
          text: 'วันพักงานสิ้นสุด',
          datafield: 'penalty_end',
          filtertype: 'date',
          cellsformat: 'dd/MM/yyyy',
          width: 130
        },
        {
          text: 'Remark',
          datafield: 'remark',
          width: 150
        },
        // { text: 'อายุใบเตือน',width: 150 },
        {
          text: 'Company',
          datafield: 'company',
          width: 80
        },
        {
          text: 'วันหมดอายุ',
          datafield: 'expiry_date',
          filtertype: 'date',
          cellsformat: 'dd/MM/yyyy',
          width: 90
        },
        {
          text: 'Created By',
          datafield: 'created_by',
          width: 90
        },
        {
          text: 'สาเหตุที่ยกเลิก',
          datafield: 'REJECTREMARK',
          width: 150
        },
      ]
    });

  }

  function submitajax(mode, warning_no, Namecheck) {


    $.ajax({
      type: 'get',
      url: '/getdata/Cancelcase',
      data: {
        "warning_no": warning_no,
        "Namecheck": Namecheck,


      },
      dataType: "JSON",
      success: function(response) {
        if (response.result == true) {
          //alert('Success !');
          location.reload();
          // $('#grid').jqxGrid('updatebounddata');
        } else {
          alert('เกิดข้อผิดพลาด ! \nสาเหตุ : ' + response);
          // console.log(response);
        }
        // console.log(response);
      }
    });

  }
</script>

@endsection