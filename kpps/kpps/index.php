<?php include 'header.php';
include '../koneksi.php';




$quero = "SELECT * FROM tps";
$stmt = $conn->query($quero);
$datoo = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tpsData = [];

foreach ($datoo as $row) {
    $ttps = $row['desa'];
    if (!isset($ttpsData[$ttps])) {
        $ttpsData[$ttps] = [
            'suara_diterima' => 0,
            'dpt' => 0,
        ];
    }
    $ttpsData[$ttps]['suara_diterima'] += $row['suara_diterima'];
    $ttpsData[$ttps]['dpt'] += $row['dpt'];
}


        $stmt = $conn->query($quero);
        $dato = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tpsData = [];

        foreach ($dato as $row) {
            $tps = $row['kec'];
            if (!isset($tpsData[$tps])) {
                $tpsData[$tps] = ['suara_diterima' => 0];
            }
            $tpsData[$tps]['suara_diterima'] += $row['suara_diterima'];
        }
        

        $query = "SELECT pemilu.id, tps.notps, calon.nama_calon, pemilu.pemp, pemilu.peml
            FROM pemilu
            INNER JOIN tps ON pemilu.id_tps = tps.id
            INNER JOIN calon ON pemilu.id_calon = calon.id
            ORDER BY calon.id, tps.notps";

        $stmt = $conn->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $calonData = [];

        foreach ($data as $row) {
            $calon = $row['nama_calon'];
            if (!isset($calonData[$calon])) {
                $calonData[$calon] = ['total_laki' => [], 'total_perempuan' => []];
            }
            $calonData[$calon]['total_laki'][] = $row['pemp'];
            $calonData[$calon]['total_perempuan'][] = $row['peml'];
        }
        $quera = "SELECT SUM(tps.dpt) AS total_dpt
                            FROM tps";

                $stmt = $conn->query($quera);
                $dats = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $quers = "SELECT SUM(pemilu.peml) AS total
                            FROM pemilu";

                $stmt = $conn->query($quers);
                $datd = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="container">
        
        <div class="mt-5 card">
<div class="card-header">
    Progres Per Kecamatan
  </div>
  <div class="card-body">

  <canvas id="ttpsChart"></canvas>
        <p>Status: <span id="status"></span></p>
    </div>
        
  </div>


<div class="mt-5 card">
<div class="card-header">
    Data Pemilih
  </div>
  <div class="card-body">

        <canvas id="chart"></canvas>
        <p>Status: <span id="status"></span></p>
    </div>
        
  </div>
    </div>
<div class="container col mt-2">
            <div class="row">
                <div class="col mt-2">
                <div class="card">
  <div class="card-header">
    Data Pemilih masing Masing Tps
  </div>
  <div class="card-body">
  <canvas id="calonChart"></canvas>
        <p>Status: <span id="status"></span></p>
  </div>
</div>
                </div>
                <div class="container col mt-2">
                <div class="card">
  <div class="card-header">
    Surat Suara Masuk
  </div>
  <ul class="list-group list-group-flush">
  <?php foreach ($datd as $row) { ?>
    <li class="list-group-item">Total Suara masuk : <?php echo $row['total']; ?></li>
    
    <?php  } ?>
  </ul>
</div>

<div class="card mt-3">
  <div class="card-header">
    Total Data DPT
  </div>
  <ul class="list-group list-group-flush">
  <?php foreach ($dats as $row) { ?>
    <li class="list-group-item">Terdata Pemilih DPT : <?php echo $row['total_dpt']; ?></li>
    <?php  } ?>
  </ul>
</div>
                    
                </div>
            </div>
        
        
        </div>
        <div class="container col mt-2">
            <div class="row">
                <div class="col mt-2">
                <div class="card">
  <div class="card-header">
    Data Pemilih masing Masing Tps
  </div>
  <div class="card-body">
    <canvas id="tpsChart"></canvas>
  </div>
</div>
                </div>
                <div class="container col mt-2">

                    
                </div>
            </div>
        
        
        </div>

</div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        

        var ctx = document.getElementById('tpsChart').getContext('2d');
        var tpsChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_keys($tpsData)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($tpsData, 'suara_diterima')); ?>,
                    label: 'Pemilih',
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display : false
                    },
                }
            }
        });

// BATAAAAAAAAAAAAS
       

        var ctx = document.getElementById('calonChart').getContext('2d');
        var calonChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($calonData)); ?>,
                datasets: [ {
                    label: 'Pemilih',
                    data: <?php echo json_encode(array_map('array_sum', array_column($calonData, 'total_perempuan'))); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },

                }
            }
        });

        //BATAAAAAAAAAAS
        var ctx = document.getElementById('chart').getContext('2d');

// Data dari PHP
var data = <?php

    $queryPemilu = "SELECT SUM(peml) AS total_pemilih FROM pemilu;";
    $stmtPemilu = $conn->query($queryPemilu);
    $dataPemil = $stmtPemilu->fetch(PDO::FETCH_ASSOC);

    $queryTPS = "SELECT SUM(dpt) AS total_dpt FROM tps;";
    $stmtTPS = $conn->query($queryTPS);
    $dataTP = $stmtTPS->fetch(PDO::FETCH_ASSOC);

    echo json_encode([$dataPemil, $dataTP]);
?>;
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('ttpsChart').getContext('2d');

    var labels = <?php echo json_encode(array_keys($ttpsData)); ?>;
    var suaraDiterimaData = <?php echo json_encode(array_column($ttpsData, 'suara_diterima')); ?>;
    var dptData = <?php echo json_encode(array_column($ttpsData, 'dpt')); ?>;

    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Suara Diterima',
                    data: suaraDiterimaData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                },
                {
                    label: 'DPT',
                    data: dptData,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                },
            ],
        },
    });
});
var chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Rekap Pemilu'],
        datasets: [
            {
                label: 'Surat Suara Masuk',
                data: [data[0].total_pemilih],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            },
            {
                label: 'Total DPT',
                data: [data[1].total_dpt],
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: true
            }
        }
    }
});

// Cek status
var statusElement = document.getElementById('status');
var pemilu = data[0].total_pemilih;
var tps= data[1].total_dpt;

if (tps < pemilu) {
    statusElement.textContent = 'Dipertanyakan!';
} else {
    statusElement.textContent = 'Layak';
}

    </script>
<?php include 'footer.php' ?>