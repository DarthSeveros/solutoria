@extends('layouts.template')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')

<div class="container">
  <a href="{{ route('indicadoresFromApi.update')}}" class="btn btn-primary">Obtener datos API</a>
  <canvas id="myChart"></canvas>
  <form>
    <div class="col-sm-2 my-3">
      <label for="fecha" class="form-label">Desde:</label>
      <input type="date" class="form-control" id="inputFechaInicio">
    </div>
    <div class="col-sm-2 my-3">
      <label for="fecha" class="form-label">Hasta:</label>
      <input type="date" class="form-control" id="inputFechaFinal">
    </div>
  </form>
  <button class="btn btn-primary" id="buttonRangoFecha">Enviar</button>
</div>

    <div class="container mt-5">
        <h1 class="mb-5">Indicadores Financieros</h1>
        <button id="createButton" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#editModal">Nuevo</button>
        <div class="row" id="IndicadoresList"></div>
        <button class="btn btn-primary" id="loadMore">Cargar más</button>
    </div>
    
  
  <!-- Modal edit-->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Editar Indicador</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="row d-flex justify-content-center">
              <div class="col-md-10 mb-3">
                <label for="inputNombre" class="form-label">Nombre indicador:</label>
                <input type="text" class="form-control" id="inputNombre" required>
              </div>
            </div>
          <div class="row d-flex justify-content-center">
              <div class="col-md-10 mb-3">
                <label for="inputCodigo" class="form-label">Código:</label>
                <input type="text" class="form-control" id="inputCodigo" required>  
              </div>
          </div>
          <div class="row d-flex justify-content-center">
              <div class="col-md-10 mb-3">
                <label for="inputFecha" class="form-label">Fecha indicador:</label>
                <input type="date" class="form-control" id="inputFecha" required>  
              </div>
          </div>
          <div class="row d-flex justify-content-center">
              <div class="col-md-6 mb-3">
                <label for="inputValor" class="form-label">Valor:</label>
                <input type="float" class="form-control" id="inputValor" required>  
              </div>
              <div class="col-md-4 mb-3">
                <label for="inputUnidadMedida" class="form-label">Unidad de medida:</label>
                <input type="text" class="form-control" id="inputUnidadMedida" required>  
              </div>
          </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary btn-update" style="display: none">Guardar cambios</button>
          <button type="button" class="btn btn-primary btn-create" style="display: none">Crear</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal delete-->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="deleteModalLabel">Eliminar Indicador</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ¿Estas seguro que deseas eliminar <span class="modal-info-name"></span>?
          <p class="mb-0">Código: <span class="modal-info-code"></span></p>
          <p>Fecha: <span class="modal-info-date"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-outline-danger" data-id="">Eliminar</button>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('assets/js/indicador/indicador.js') }}" defer></script>
      
@endsection