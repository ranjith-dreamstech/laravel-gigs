'use strict';

$(document).ready(function() {

	function generateData(baseval, count, yrange) {
		var i = 0;
		var series = [];
		while (i < count) {
			var x = Math.floor(Math.random() * (750 - 1 + 1)) + 1;;
			var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
			var z = Math.floor(Math.random() * (75 - 15 + 1)) + 15;

			series.push([x, y, z]);
			baseval += 86400000;
			i++;
		}
		return series;
	}

  if($('#purchase-chart').length > 0 ){
    var options = {
      series: [{
        name: "Purchase",
        colors: ['#FF6900'],
        data: [{
          x: 'jan',
          y: 7.0,              
            }, {
              x: 'Feb',
              y: 4.0
            }, {
              x: 'Mar',
              y: 3.0
            }, {
              x: 'Apr',
              y: 3.7
            }, {
              x: 'May',
              y: 6.0
            },{
              x: 'Jun',
              y: 2.0
            },{
              x: 'Jul',
              y: 6.5
            },{
              x: 'Aug',
              y: 2.0
            },{
              x: 'Sep',
              y: 3.0
            },{
              x: 'Oct',
              y: 2.0
            },{
              x: 'Nov',
              y: 5.0
            },{
              x: 'Dec',
              y: 7.0
            }]
          }],
            chart: {
            type: 'bar',
            height: 250,            
            toolbar: {
              show: false,
            }
          },
          dataLabels: {
            enabled: false
          },
          plotOptions: {
            bar: {
                columnWidth: '20%',
                borderRadius: 0,
                endingShape: 'rounded'
            }
          },
          colors: ['#FF6900'],
          
          };    
          var chart = new ApexCharts(document.querySelector("#purchase-chart"), options);
          chart.render();
    }	


    // sales income

    // sales income

  if ($('#sales-income').length > 0) {
    var sColStacked = {
      chart: {
        height: 290,
        type: 'bar',
        stacked: true,
        toolbar: {
          show: false,
        }
      },
      colors: ['#FF6F28', '#F8F9FA'],
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            position: 'bottom',
            offsetX: -10,
            offsetY: 0
          }
        }
      }],
      plotOptions: {
        bar: {
          borderRadius: 5, 
          borderRadiusWhenStacked: 'all',
          horizontal: false,
          endingShape: 'rounded'
        },
      },
      series: [{
        name: 'Income',
        data: [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 160, 150, 140, 130, 120, 110, 100, 90, 80, 70, 60, 50, 40, 30, 20, 10]
      }, {
        name: 'Expenses',
        data: [60, 70, 55, 20, 15, 10, 20, 20, 20, 15, 80, 20]
      }],
      xaxis: {
        categories: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11', '12','13', '14', '15', '16', '17', '18', '19', '20', '21', '22','23', '24','25', '26', '27','28', '29' , '30'],
        labels: {
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      yaxis: {
        labels: {
          offsetX: -15,
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      grid: {
        borderColor: '#E5E7EB',
        strokeDashArray: 5,
        padding: {
          left: -8,
        },
      },
      legend: {
        show: false
      },
      dataLabels: {
        enabled: false // Disable data labels
      },
      fill: {
        opacity: 1
      },
    }

    var chart = new ApexCharts(
      document.querySelector("#sales-income"),
      sColStacked
    );

    chart.render();
  }
    

    if($('#amount-chart').length > 0 ){
    var options = {
      series: [{
        name: "Sales",
        colors: ['#FF6900'],
        data: [{
          x: 'jan',
          y: 7.0,              
            }, {
              x: 'Feb',
              y: 7.0
            }, {
              x: 'Mar',
              y: 3.0
            }, {
              x: 'Apr',
              y: 8.7
            }, {
              x: 'May',
              y: 7.0
            },{
              x: 'Jun',
              y: 2.0
            },{
              x: 'Jul',
              y: 7.5
            },{
              x: 'Aug',
              y: 2.0
            },{
              x: 'Sep',
              y: 3.0
            },{
              x: 'Oct',
              y: 2.0
            },{
              x: 'Nov',
              y: 5.0
            },{
              x: 'Dec',
              y: 7.0
            }]
          }],
            chart: {
            type: 'bar',
            height: 250,            
            toolbar: {
              show: false,
            }
          },
          dataLabels: {
            enabled: false
          },
          plotOptions: {
            bar: {
                columnWidth: '20%',
                borderRadius: 0,
                endingShape: 'rounded'
            }
          },
          colors: ['#FF6900'],
          
          };    
          var chart = new ApexCharts(document.querySelector("#amount-chart"), options);
          chart.render();
    } 

    if ($('#s-col').length > 0) {
      var sCol = {
        chart: {
          height: 290,
          type: 'bar',
          toolbar: {
            show: false,
          }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '80%',
            borderRadius: 5, 
            endingShape: 'rounded', // This rounds the top edges of the bars
          },
        },
        colors: ['#FF781A', '#45505C'],
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        
        series: [{
          name: 'Revenue',
          data: [510, 320, 440, 600, 450, 150, 580, 190, 430, 290, 190, 290]
        }, {
          name: 'Withdrawn',
          data: [290, 120, 280, 190, 290, 290, 290, 110, 120, 130, 290, 120]
        }],
        xaxis: {
          categories: ['Jan', 'Feb', 'May', 'Mar', 'Jun', 'July', 'Aug', 'Apr', 'Sep', 'Oct', 'Nov', 'Dec'],
          labels: {
            style: {
              colors: '#5D6772', 
              fontSize: '14px',
            }
          }
        },
        yaxis: {
          min: 0,
          max: 600,
          labels: {
            offsetX: -15,
            style: {
              colors: '#5D6772', 
              fontSize: '13px',
            }
          }
        },
        grid: {
          borderColor: '#E2E4E6',
          strokeDashArray: 5,
          padding: {
            left: -8,
            right: -15, 
          },
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "" + val + "%"
            }
          }
        }
      }
    
      var chart = new ApexCharts(
        document.querySelector("#s-col"),
        sCol
      );
    
      chart.render();
    }

  
});

