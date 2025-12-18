<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
  <div class="mb-6">
    <x-card>
      <div class="mb-4 flex items-center justify-between">
        <h3 class="text-lg font-semibold">{{ __('ESP32 Latest (50) TDS Readings') }}</h3>
      </div>
      <div id="esp32-activity-chart-TDS" class="h-80"></div>
      <div class="mb-4 flex items-center justify-between">
        <h3 class="text-lg font-semibold">{{ __('ESP32 Latest (50) Temp. Readings') }}</h3>
      </div>	  
	  <div id="esp32-activity-chart-TEMP" class="h-80"></div>
    </x-card>
  </div>

  <livewire:esp32data::components.esp32-datatable lazy />

  @push('scripts')
  {{-- If ApexCharts is not globally included by your layout, include it here --}}
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const stats = @json($esp32_stats ?? ['value1'=>[], 'value2'=>[], 'value3'=>[]]);

	//For TDS graph
      const optionsTds = {
          series: [
              { name: 'Value 1', data: stats.value1 },
              //{ name: 'Value 2', data: stats.value2 },
              //{ name: 'Value 3', data: stats.value3 },
          ],
          chart: {
              type: 'area',
              height: 320,
              fontFamily: 'var(--font-sans)',
              toolbar: { show: true }
          },
          stroke: { width: 2, curve: 'smooth' },
          dataLabels: { enabled: false },
          markers: { size: 0 },
          xaxis: {
              type: 'datetime',
	
              labels: { style: { colors: '#64748b', fontFamily: 'var(--font-sans)' } }
          },
          yaxis: {
              decimalsInFloat: 2,
              labels: { style: { colors: '#64748b', fontFamily: 'var(--font-sans)' } }
          },
          legend: { position: 'top' },
          grid: { borderColor: '#e2e8f0', strokeDashArray: 4 }
      };
	  
	  //For TEMP graph
	   const optionsTemp = {
          series: [
              //{ name: 'Value 1', data: stats.value1 },
              { name: 'Value 2', data: stats.value2 },
              //{ name: 'Value 3', data: stats.value3 },
          ],
          chart: {
              type: 'area',
              height: 320,
              fontFamily: 'var(--font-sans)',
              toolbar: { show: true }
          },
          stroke: { width: 2, curve: 'smooth' },
          dataLabels: { enabled: false },
          markers: { size: 0 },
          xaxis: {
              type: 'datetime',
              labels: { style: { colors: '#64748b', fontFamily: 'var(--font-sans)' } }
          },
          yaxis: {
              decimalsInFloat: 2,
              labels: { style: { colors: '#64748b', fontFamily: 'var(--font-sans)' } }
          },
          legend: { position: 'top' },
          grid: { borderColor: '#e2e8f0', strokeDashArray: 4 }
      };
	  

      const eltds = document.querySelector('#esp32-activity-chart-TDS');
	  const eltemp = document.querySelector('#esp32-activity-chart-TEMP');
      if (eltds && window.ApexCharts) {
          const chartTds = new ApexCharts(eltds, optionsTds);
          chartTds.render();
		  const chartTemp = new ApexCharts(eltemp, optionsTemp);
          chartTemp.render();
      } else {
          console.error('ApexCharts not found or chart element missing.');
      }
  });
  </script>
  @endpush
</x-layouts.backend-layout>