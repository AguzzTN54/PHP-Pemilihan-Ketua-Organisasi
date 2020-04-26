<?php

$kandidat = new Kandidat;
$suara = new Suara;
$user = new User;

$jumlahKandidat = $kandidat->jumlah;
$jumlahSuara = $suara->jumlahSah;

if($do == 'kandidat'){
  if($act == 'app'){
    if($kandidat->cek($id)){

      $tampil = $suara->kandidat($id);
      if($suara->kandidat($id, 'hitung')>0){
        for($i=0; $i<$suara->kandidat($id, 'hitung');$i++){
          $tableField .= '<tr>
          <td>'.($i+1).'</td>
          <td> Satu suara ditambahkan</td>
          <td> '. dateToIndo($tampil[$i]['waktu']).'</td>
          </tr>';
        }
      }else{
        $tableField = '<tr><td></td><td>tyduck ada</td><td></td> </tr>';
      }
    }else{
      $tableField = '<tr><td><td></td>tyduck ada</td><td></td></tr>';
    }
?>

<div class="row">
  <div class="col-4">
    <div class="rounded-circle overflow-hidden d-flex justify-content-center align-items-center mb-1"
      style="width:100px; height:100px;">
      <img src="../images/kandidat/<?=$kandidat->show($id, 'foto')?>" alt="Kandidat" style="width:100%">
    </div>
  </div>
  <div class="col-8">
    <h4 class="sub-title"><?=$kandidat->show($id, 'namaKandidat')?></h4>
    Total Suara : <span style="font-size:xx-large"><?=$suara->kandidat($id, 'hitung')?></span>
  </div>
</div>
<table id="dataTables" class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Keterangan</th>
      <th>Tanggal</th>
    </tr>
  </thead>
  <tbody>
    <?=$tableField?>
  </tbody>
</table>

<?php
  }else{
    header('location:'.$adminHome.'kandidat');
  }
  
}else{

  $perolehan = '';
  for ($i=0; $i < $kandidat->jumlah; $i++) {
    $idKandidat = $kandidat->tampil[$i]['idKandidat'];
    $namaKandidat = $kandidat->tampil[$i]['namaKandidat'];
    $jurusan = $kandidat->tampil[$i]['jurusan'];
    $foto = $kandidat->tampil[$i]['foto'];
    $suaraDidapat = $suara->kandidat($idKandidat,'hitung');
    if($suaraDidapat == $suara->cekPerolehan('tertinggi')){
      if($pemilu){
        $aktif = '';
      $badge = '';
      }else{
        $aktif = ' active';
        $badge = '<div class="badge">
        <img src="../images/badge.svg" alt="badge">
      </div>';
      }
    }else{
      $aktif = '';
      $badge = '';
    }

    if($pemilu){
      $chartData[] = [];
    }else{
      $chartData[] = array(
        'name' => explode(' ',$namaKandidat)[0],
        'steps' => $suaraDidapat,
        'img' => '../images/kandidat/'.$foto
      );
    }
    $perolehan .= '<div class="col-lg-3 col-sm-6 col-11 pt-4">
    <div class="hasil-item card p-2 rounded shadow bg-white text-center w-100'.$aktif.'">
      <span class="nomor-urut">'.($i+1).'</span>
      <div class="thumb rounded-circle shadow overflow-hidden d-flex justify-content-center align-items-center">
        <img src="../images/kandidat/'.$foto.'" alt="Kandidat ke '.($i+1).'" style="max-width:100%">
      </div>
      '.$badge.'
      <h5>'.$namaKandidat.'</h5>
      <span class="text-utama" style="margin-top:-8px;">'.$jurusan.'</span>
      <span class="info-body suara-sah" data-toggle="counter-up"> '.$suaraDidapat .'</span>
      <span class="info-title">Suara Didapat</span>
    </div>
  </div>';
  }

  if($pemilu){
echo '<div class="disabled-content">
  <p>Hasil Pemilu tidak dapat diakses selama Pemilu Sedang Berlangsung</p>
</div>';
  }
?>

<div class="d-flex align-items-center mb-5 wow fadeInUp" style="justify-content:space-between;" data-wow-duration="1.7s"
  data-wow-delay="1s">
  <h3 class="sub-title" style="font-weight:bold;">hasil perolehan suara <button class="btn ml-2 reset">Reset</button>
  </h3>
  <button class="btn btn-outline-dark print" style="margin:0 30px 0 10px" onclick="window.print()"> <i
      class="fa fa-print"></i>
    Print</button>
</div>

<div class="row align-items-center suara-kandidat pr-2 mt-1 wow fadeInUp"
  style="justify-content: space-evenly; margin-top: -20px;" data-wow-duration="1.7s" data-wow-delay="1s">
  <?=$perolehan;?>
</div>

<div class="d-flex align-items-center w-100 mt-5 text-white wow fadeInUp"
  style="justify-content: space-evenly; flex-wrap:wrap" data-wow-duration="1.7s" data-wow-delay="1s">
  <div class="rounded rangkuman bg-ijo">
    <div class="jenis-suara">total <span class="d-block">Suara Sah</span></div>
    <span style="font-size: xx-large; font-weight: bold;" data-toggle="counter-up"><?=$suara->jumlahSah;?></span>
  </div>
  <div class="rounded rangkuman bg-warn">
    <div class="jenis-suara">total <span class="d-block">Tidak Memilih</span></div>
    <span style="font-size: xx-large; font-weight: bold;"
      data-toggle="counter-up"><?=$suara->dataSuara('golput');?></span>
  </div>
  <div class="rounded rangkuman bg-abang">
    <div class="jenis-suara">Hak Suara <span class="d-block">Tak Terpakai</span></div>
    <span style="font-size: xx-large; font-weight: bold;"
      data-toggle="counter-up"><?=$suara->dataSuara('sisaSuara');?></span>
  </div>
  <div class="rounded rangkuman bg-biru">
    <div class="jenis-suara">Total <span class="d-block">Suara Masuk</span></div>
    <span style="font-size: xx-large; font-weight: bold;"
      data-toggle="counter-up"><?=$suara->dataSuara('sudahMasuk');?></span>
  </div>
  <div class="rounded rangkuman bg-gelap">
    <div class="jenis-suara">Total <span class="d-block">Pemilik Suara</span></div>
    <span style="font-size: xx-large; font-weight: bold;" data-toggle="counter-up"><?=$user->jumlahUser;?></span>
  </div>
</div>

<div class="shadow bg-white rounded p-3 mt-2 row">
  <div class="col-md-7">
    <h3 class="sub-title">Chart</h3>
    <div id="chartdiv" class="chart rounded"></div>
  </div>

  <div class="col-md-5">
    <div id="donut-chart" class="chart"></div>
  </div>
</div>

<script src="./assets/js/hasil.js"></script>
<script src="./assets/js/chart/core.js"></script>
<script src="./assets/js/chart/charts.js"></script>
<script src="./assets/js/chart/animated.js"></script>

<?= '<script>'?>
am4core.useTheme(am4themes_animated);

var chart = am4core.create("chartdiv", am4charts.XYChart);


chart.paddingBottom = 60;

chart.data = <?= json_encode($chartData);?>;

var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "name";
categoryAxis.renderer.grid.template.strokeOpacity = 0;
categoryAxis.renderer.minGridDistance = 10;
categoryAxis.renderer.labels.template.dy = 55;
categoryAxis.renderer.tooltip.dy = 55;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.inside = true;
valueAxis.renderer.labels.template.fillOpacity = 0.3;
valueAxis.renderer.grid.template.strokeOpacity = 0;
valueAxis.min = 0;
valueAxis.cursorTooltipEnabled = false;
valueAxis.renderer.baseGrid.strokeOpacity = 0;

var series = chart.series.push(new am4charts.ColumnSeries);
series.dataFields.valueY = "steps";
series.dataFields.categoryX = "name";
series.tooltipText = "{valueY.value}";
series.tooltip.pointerOrientation = "vertical";
series.tooltip.dy = -6;
series.columnsContainer.zIndex = 100;

var columnTemplate = series.columns.template;
columnTemplate.width = am4core.percent(70);
columnTemplate.maxWidth = 70;
columnTemplate.column.cornerRadius(60, 60, 10, 10);
columnTemplate.strokeOpacity = 0;

series.heatRules.push({
target: columnTemplate,
property: "fill",
dataField: "valueY",
min: am4core.color("#36b9cc"),
max: am4core.color("#185fc9")
});
series.mainContainer.mask = undefined;

var cursor = new am4charts.XYCursor();
chart.cursor = cursor;
cursor.lineX.disabled = false;
cursor.lineY.disabled = false;
cursor.behavior = "none";

var bullet = columnTemplate.createChild(am4charts.CircleBullet);
bullet.circle.radius = 35;
bullet.valign = "bottom";
bullet.align = "center";
bullet.isMeasured = true;
bullet.interactionsEnabled = false;
bullet.verticalCenter = "bottom";

var hoverState = bullet.states.create("hover");

var outlineCircle = bullet.createChild(am4core.Circle);
outlineCircle.adapter.add("radius", function(radius, target) {
var circleBullet = target.parent;
return circleBullet.circle.pixelRadius + 10;
})

var image = bullet.createChild(am4core.Image);
image.width = 120;
image.height = 120;
image.horizontalCenter = "middle";
image.verticalCenter = "middle";

image.adapter.add("href", function(href, target) {
var dataItem = target.dataItem;
if (dataItem) {
// return dataItem.categoryX.toLowerCase() + ".jpg";
return dataItem._dataContext.img;
}
})


image.adapter.add("mask", function(mask, target) {
var circleBullet = target.parent;
return circleBullet.circle;
})

var previousBullet;
chart.cursor.events.on("cursorpositionchanged", function(event) {
var dataItem = series.tooltipDataItem;

if (dataItem.column) {
var bullet = dataItem.column.children.getIndex(1);

if (previousBullet && previousBullet != bullet) {
previousBullet.isHover = false;
}

if (previousBullet != bullet) {

var hs = bullet.states.getKey("hover");
hs.properties.dy = -bullet.parent.pixelHeight + 30;
bullet.isHover = true;

previousBullet = bullet;
}
}
})
</script>

<?='<script>'?>
am4core.useTheme(am4themes_animated);

var chart = am4core.create("donut-chart", am4charts.PieChart);


chart.data = <?= json_encode($chartData);?>;

var series = chart.series.push(new am4charts.PieSeries());
series.dataFields.value = "steps";
series.dataFields.category = "name";

// this creates initial animation
series.hiddenState.properties.opacity = 1;
series.hiddenState.properties.endAngle = -90;
series.hiddenState.properties.startAngle = -90;

chart.legend = new am4charts.Legend();
</script>

<?php
}
?>