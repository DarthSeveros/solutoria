import Chart from 'chart.js/auto';

"use strict";

$(loadData);
$(getData);
let myChart;


// Funcion obtener datos por rango de fecha
$(function () {
  $('#buttonRangoFecha').on('click', () => {
    let fechaInicio = $('#inputFechaInicio').val();
    let fechaFinal = $('#inputFechaFinal').val();
    let data = {'fechaInicio': fechaInicio, 'fechaFinal':fechaFinal}
    $.ajax({
      method: "GET",
      url: '/solicitud/data/fecha',
      data: data,
      success: (result) => {
        updateChart(result);
      },
      error: (error) => console.error("Error al traer los indicadores: ", error)
    });
  })
})

// Actualizar el gráfico con arreglo de [valores, fechas]
function updateChart(data) {
  myChart.data.labels = data.fechas;
  myChart.data.datasets = [{
    label: 'Valor UF',
    data: data.valores,
    borderWidth: 1
  }]
  myChart.update();
}

// Creación del gráfico con arreglo [valores, fechas]
function createChart(data) {
  const ctx = document.getElementById('myChart');

  myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: data.fechas,
      datasets: [{
        label: 'Valor UF',
        data: data.valores,
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: false
        }
      }
    }
  });
}

// Obtención de datos para crear gráfico
function getData() {
  $.ajax({
    method: "GET",
    url: '/solicitud/data',
    success: (result) => {
      createChart(result);
    },
    error: (error) => console.error("Error al traer los indicadores: ", error)
  });
}

// Obtención de todos los datos 
function loadData() {
  $.ajax({
    method: "GET",
    url: '/solicitud/lista',
    success: (result) => {
      setIndicadores(result['data']);
    },
    error: (error) => console.error("Error al traer los indicadores: ", error)
  });
}

// Creación de nuevo indicador financiero
function createIndicador(data) {
  $.ajax({
    method: "POST",
    url: "/solicitud/create",
    data: data,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: (result) => {
      $('#editModal').modal("hide");
      loadData();
    },
    error: (error) => console.error("Error al crear el indicador: ", error)
  });
}

// Actualización de un indicador finaciero ya existente
function updateIndicador(id, data) {
  $.ajax({
    method: "PATCH",
    url: "/solicitud/update/" + id,
    data: data,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: (result) => {
      $('#editModal').modal("hide");
      loadData();
    },
    error: (error) => console.error("Error al actualizar el indicador: ", error)
  });
}

// Asignación de información de los indicadores a una tarjeta en html con sus respectivos botones
// para editar y elimnar
function setIndicadores(indicadores, append = false) {
  if (!append) {
    $('#IndicadoresList').html("");
  }
  indicadores.forEach((indicador) => {
    $('#IndicadoresList').append(
      `<div class="card mb-3 me-3" style="width: 18rem;">
  <div class="card-body">
      <h5 class="card-title name">${indicador.nombreIndicador} </h5>
      <h6 class="card-subtitle mb-1 text-muted code">${indicador.codigoIndicador}</h6>
      <h6 class="card-subtitle mb-2 text-muted date">${indicador.fechaIndicador}</h6>
      <p class="card-text">\$<span class="value">${indicador.valorIndicador}</span> <span class="unit">${indicador.unidadMedidaIndicador}</span></p>
      <button class="btn btn-primary edit-button" data-bs-toggle="modal" data-bs-target="#editModal" data-id=${indicador.id}>Editar</button>
      <button class="btn btn-outline-danger delete-button" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id=${indicador.id}>Eliminar</button>
  </div>
</div>`
    )
  }
  );

  $('.delete-button').on('click', function () {
    $('#deleteModal .btn-outline-danger').attr('data-id', this.dataset.id);
    let name = $(this).siblings('.name').text();
    let code = $(this).siblings('.code').text();
    let date = $(this).siblings('.date').text();
    $('#deleteModal .modal-info-name').text(name);
    $('#deleteModal .modal-info-code').text(code);
    $('#deleteModal .modal-info-date').text(date);
  })

  $('.edit-button').on('click', function () {
    $('#editModal .btn-create').hide();
    $('#editModal .btn-update').show();
    $('#editModal .btn-primary').attr('data-id', this.dataset.id);
    let name = $(this).siblings('.name').text();
    let code = $(this).siblings('.code').text();
    let date = $(this).siblings('.date').text();
    let value = $(this).siblings('.card-text').children('.value').text();
    let unit = $(this).siblings('.card-text').children('.unit').text();
    $('#inputNombre').val(name);
    $('#inputCodigo').val(code);
    $('#inputFecha').val(date);
    $('#inputValor').val(value);
    $('#inputUnidadMedida').val(unit);
  });

  $('#createButton').on('click', function () {
    $('#editModal .btn-update').hide();
    $('#editModal .btn-create').show();
    $('#inputNombre').val('');
    $('#inputCodigo').val('');
    $('#inputFecha').val('');
    $('#inputValor').val('');
    $('#inputUnidadMedida').val('');
  });

  $('#editModal .btn-create').on('click', function () {
    let data = {
      nombreIndicador: $('#inputNombre').val(),
      codigoIndicador: $('#inputCodigo').val(),
      fechaIndicador: $('#inputFecha').val(),
      valorIndicador: $('#inputValor').val(),
      unidadMedidaIndicador: $('#inputUnidadMedida').val()
    }
    createIndicador(data);
  });

  $('#editModal .btn-update').on('click', function () {
    let data = {
      nombreIndicador: $('#inputNombre').val(),
      codigoIndicador: $('#inputCodigo').val(),
      fechaIndicador: $('#inputFecha').val(),
      valorIndicador: $('#inputValor').val(),
      unidadMedidaIndicador: $('#inputUnidadMedida').val()
    }
    updateIndicador(this.dataset.id, data);
  });
}

// Función ejecutada al momento de confirmar la eliminación de un dato
$(function () {
  $('.modal-footer .btn-outline-danger').on('click', function () {
    $.ajax({
      method: "DELETE",
      url: '/solicitud/delete/' + $(this).attr('data-id'),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: () => {
        $('#deleteModal').modal("hide");
        loadData();
      },
      error: (error) => console.error("Error al eliminar elemento: ", error)
    });
  })
})

// Función para cargar más elementos en pantalla
$(function () {
  $('#loadMore').on('click', () => {
    $.ajax({
      method: "GET",
      url: '/solicitud/lista',
      success: (result) => {
        setIndicadores(result['data'], true);
      },
      error: (error) => console.error("Error al traer los indicadores: ", error)
    });
  })
})