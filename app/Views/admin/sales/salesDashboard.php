<?
$_Link = "sid=".$_request->getGet('sid')."&page=".$_request->getGet('page');

$getYear = ($_request->getGet('year')?$_request->getGet('year'):date("Y"));
$getMonth = ($_request->getGet('month')?$_request->getGet('month'):date("n"));
$getDay = ($_request->getGet('day')?$_request->getGet('day'):date("j"));

$getStartDate = ($_request->getGet('startdate')?$_request->getGet('startdate'):date("Y-m-d", strtotime("-7 Day")));
$getEndDate = ($_request->getGet('enddate')?$_request->getGet('enddate'):date("Y-m-d"));
?>
<style>
   .tab-content { padding: 0; }
  .mouseover { background-color: #f5f5f5s!important; }
</style>
<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y" style="overflow-x:scroll">
  <h4 class="fw-bold py-3 mb-0">매출 현황<button type="button" class="btn btn-primary btn-xs btn-help" data-bs-toggle="modal" data-bs-target="#salesHelp">Help</button></h4>

  <div class="row">
    <div class="col-sm-6 row">
    </div>
    <div class="col-sm-6 text-end">
      <a href="/admin/sales/ShopList?<?=$_Link?>" class="btn btn-secondary">목록</a>
    </div>
  </div>

  <!-- Basic Bootstrap Table -->

  <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link <?=(!$_request->getGet('tabs')||$_request->getGet('tabs')=="tab1"?"active":"")?>" id="tab1-tab" data-bs-toggle="tabs" href="?<?=$_Link?>&tabs=tab1" role="tabs" aria-controls="tab1" aria-selected="<?=($_request->getGet('tabs')?"true":"false")?>">최근매출요약</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?=($_request->getGet('tabs')=="tab2"?"active":"")?>" id="tab2-tab" data-bs-toggle="tabs" href="?<?=$_Link?>&tabs=tab2" role="tabs" aria-controls="tab2" aria-selected="false">월/일/시간별 매출</a>
    </li>
    <!--li class="nav-item">
      <a class="nav-link <?=($_request->getGet('tabs')=="tab3"?"active":"")?>" id="tab3-tab" data-bs-toggle="tabs" href="?<?=$_Link?>&tabs=tab3#tab3" role="tabs" aria-controls="tab3" aria-selected="false">메뉴별 매출</a>
    </!--li-->
  </ul>
  
  <div class="tab-content" id="myTabContent">

    <div class="tab-pane fade <?=(!$_request->getGet('tabs')||$_request->getGet('tabs')=="tab1"?"show active":"")?>" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
      <div class="card">
        <div class="card-header">
          최근 7일 매출
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div id="dailySalesChart" class="px-2"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="tab-pane fade <?=($_request->getGet('tabs')=="tab2"?"show active":"")?>" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
      <div class="row">
        <div class="col-md-3">
          <div class="card">
            <div class="card-header">
              월별 매출요약
            </div>
            <div class="card-body">
              <form id="yearSale" name="yearSale" method="get" action="?">
                <input type="hidden" name="sid" value="<?=$_request->getGet('sid')?>">
                <input type="hidden" name="page" value="<?=$_request->getGet('page')?>">
                <input type="hidden" name="tabs" value="tab2">
                <div class="row mb-3">
                  <div class="col-md-8">
                    <select name="year" id="year" class="form-select">
                      <? for($i=$startYear; $i<=date("Y"); $i++) { ?>
                      <option value="<?=$i?>" <?=($i==$getYear?"selected":"")?>><?=$i?>년</option>
                      <? } ?>
                    </select>
                  </div>
                  <button class="btn btn-primary col-md-3" href="javascript:void(0)" onclick="">검색</button>
                </div>
              </form>
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th class="text-center">월</th>
                    <th class="text-center">매출</th>
                  </tr>
                </thead>
                <tbody>
                  <? for($i=1; $i<=12; $i++) {?>
                  <tr>
                    <td class="text-center"><?=$i?>월</td>
                    <td class="text-end"><?=number_format($yearSales[$i])?></td>
                  </tr>
                  <? }?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="card">
            <div class="card-header">
              일별 매출요약
            </div>
            <div class="card-body">
              <form id="monthSale" name="monthSale" method="get" action="?">
                <input type="hidden" name="sid" value="<?=$_request->getGet('sid')?>">
                <input type="hidden" name="page" value="<?=$_request->getGet('page')?>">
                <input type="hidden" name="tabs" value="tab2">
                <input type="hidden" name="year" value="<?=$getYear?>">
                <div class="row mb-3">
                  <div class="col-md-4">
                    <select name="month" id="month" class="form-select">
                      <? for($i=1; $i<=12; $i++) { ?>
                      <option value="<?=$i?>" <?=($i==$getMonth?"selected":"")?>><?=$i?>월</option>
                      <? } ?>
                    </select>
                  </div>
                  <button class="btn btn-primary col-md-2" href="javascript:void(0)" onclick="">검색</button>
                </div>
              </form>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center">일</th>
                    <th class="text-center">매출</th>
                    <th class="text-center">일</th>
                    <th class="text-center">매출</th>
                  </tr>
                </thead>
                <tbody>
                  <? for($i=1; $i<=16; $i++) {?>
                  <tr>
                    <td class="text-center valueover value-<?=$i?>" data="<?=$i?>"><?=$i?>일</td>
                    <td class="text-end valueover value-<?=$i?>" data="<?=$i?>"><?=number_format($monthSales[$i])?></td>
                    <? if(isset($monthSales[$i+16])) { ?>
                    <td class="text-center valueover value-<?=($i+16)?>" data="<?=($i+16)?>"><?=($i+16)?>일</td>
                    <td class="text-end valueover value-<?=($i+16)?>" data="<?=($i+16)?>"><?=number_format($monthSales[$i+16])?></td>
                    <? } else { ?>
                    <td class="text-center"></td>
                    <td class="text-end"></td>
                    <? } ?>
                  </tr>
                  <? }?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              시간별 매출요약
            </div>
            <div class="card-body">
              <form id="monthSale" name="monthSale" method="get" action="?">
                <input type="hidden" name="sid" value="<?=$_request->getGet('sid')?>">
                <input type="hidden" name="page" value="<?=$_request->getGet('page')?>">
                <input type="hidden" name="tabs" value="tab2">
                <input type="hidden" name="year" value="<?=$getYear?>">
                <input type="hidden" name="month" value="<?=$getMonth?>">
                <div class="row mb-3">
                  <div class="col-md-5">
                    <select name="day" id="day" class="form-select">
                      <? for($i=1; $i<=date("t", strtotime($getYear."-".$getMonth."-1")); $i++) { ?>
                      <option value="<?=$i?>" <?=($i==$getDay?"selected":"")?>><?=$i?>일</option>
                      <? } ?>
                    </select>
                  </div>
                  <button class="btn btn-primary col-md-3" href="javascript:void(0)" onclick="">검색</button>
                </div>
              </form>
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th class="text-center">시간</th>
                    <th class="text-center">매출</th>
                    <th class="text-center">시간</th>
                    <th class="text-center">매출</th>
                  </tr>
                </thead>
                <tbody>
                  <? for($i=0; $i<12; $i++) {?>
                  <tr>
                    <td class="text-center"><?=$i?>시</td>
                    <td class="text-end"><?=number_format($daySales[$i])?></td>
                    <? if(isset($daySales[$i+12])) {?>
                    <td class="text-center"><?=($i+12)?>시</td>
                    <td class="text-end"><?=number_format($daySales[$i+12])?></td>
                    <? } else {?>
                    <td class="text-center"></td>
                    <td class="text-end"></td>
                    <? }?>
                  </tr>
                  <? }?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--div class="tab-pane fade <?=($_request->getGet('tabs')=="tab3"?"show active":"")?>" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-sm-6">
              메뉴별 매출
            </div>
            <div class="col-sm-6 text-end row">
              <div class="col-sm-6 offset-sm-4">
                <div class="input-group input-daterange">
                  <input type="text" class="form-control" id="startDate" required value="<?=($getStartDate)?>">
                  <span class="input-group-text">~</span>
                  <input type="text" class="form-control" id="endDate" required value="<?=($getEndDate)?>">
                </div>
              </div>
              <div class="col-sm-2">
                <a class="btn btn-secondary" href="javascript:searchGo()">검색</a>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div id="dailySalesChart" class="px-2"></div>
          </div>
        </div>
      </div>
    </!--div-->

  </div>
</div>

<script>
  function fncPageReady() {
    $('.input-daterange').datepicker({
      format: "yyyy-mm-dd",
      language: "ko",
      autoclose: true,
    });

    setOverStyle();
    
    const totalRevenueChartEl = document.querySelector('#dailySalesChart'),
      totalRevenueChartOptions = {
        series: [
          {
            name: '2021',
            data: [<?=join(",", $dailyvals)?>]
          }
        ],
        chart: {
          height: 300,
          stacked: true,
          type: 'bar',
          toolbar: { show: false }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '33%',
            borderRadius: 12,
            startingShape: 'rounded',
            endingShape: 'rounded'
          }
        },
        colors: [config.colors.primary, config.colors.info],
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 6,
          lineCap: 'round',
          colors: "#ffffff"
        },
        legend: {
          show: true,
          horizontalAlign: 'left',
          position: 'top',
          markers: {
            height: 8,
            width: 8,
            radius: 12,
            offsetX: -3
          },
          labels: {
            colors: "#697A8D"
          },
          itemMargin: {
            horizontal: 10
          }
        },
        grid: {
          borderColor: "#b6b6b6",
          padding: {
            top: 0,
            bottom: -8,
            left: 20,
            right: 20
          }
        },
        xaxis: {
          categories: [<?=join(",", $dailydays)?>],
          labels: {
            style: {
              fontSize: '13px',
              colors: "#697A8D"
            }
          },
          axisTicks: {
            show: false
          },
          axisBorder: {
            show: false
          }
        },
        yaxis: {
          labels: {
            style: {
              fontSize: '13px',
              colors: "#697A8D"
            }
          }
        },
        responsive: [
          {
            breakpoint: 1700,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '32%'
                }
              }
            }
          },
          {
            breakpoint: 1580,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '35%'
                }
              }
            }
          },
          {
            breakpoint: 1440,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '42%'
                }
              }
            }
          },
          {
            breakpoint: 1300,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '48%'
                }
              }
            }
          },
          {
            breakpoint: 1200,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '40%'
                }
              }
            }
          },
          {
            breakpoint: 1040,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 11,
                  columnWidth: '48%'
                }
              }
            }
          },
          {
            breakpoint: 991,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '30%'
                }
              }
            }
          },
          {
            breakpoint: 840,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '35%'
                }
              }
            }
          },
          {
            breakpoint: 768,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '28%'
                }
              }
            }
          },
          {
            breakpoint: 640,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '32%'
                }
              }
            }
          },
          {
            breakpoint: 576,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '37%'
                }
              }
            }
          },
          {
            breakpoint: 480,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '45%'
                }
              }
            }
          },
          {
            breakpoint: 420,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '52%'
                }
              }
            }
          },
          {
            breakpoint: 380,
            options: {
              plotOptions: {
                bar: {
                  borderRadius: 10,
                  columnWidth: '60%'
                }
              }
            }
          }
        ],
        states: {
          hover: {
            filter: {
              type: 'none'
            }
          },
          active: {
            filter: {
              type: 'none'
            }
          }
        }
      };
    if (typeof totalRevenueChartEl !== undefined && totalRevenueChartEl !== null) {
      const totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
      totalRevenueChart.render();
    }
  } 

  function setOverStyle() {
    $('.value').mouseover(function() {
      $('.value-'+$(this).attr('data')).addClass('mouseover');
    }).mouseout(function() {
      $('.value-'+$(this).attr('data')).removeClass('mouseover');
    });
  }
</script>
<!-- / Content -->