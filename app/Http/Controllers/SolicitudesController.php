<?php

namespace App\Http\Controllers;

use App\Models\IndiceFinanciero;
use Illuminate\Http\Request;

class SolicitudesController extends Controller
{
    public function index()
    {
        $indicadores = IndiceFinanciero::all();
        return view('indices.index', compact('indicadores'));
    }

    public function getIndicadores($cantidad=20, $order=null){
        if ($order === 'valorMayorMenor'){
            return response()->json(IndiceFinanciero::orderby('valorIndicador','desc')->paginate(($cantidad)));
        }
        elseif ($order === 'valorMenorMayor'){
            return response()->json(IndiceFinanciero::orderby('valorIndicador','asc')->paginate(($cantidad)));
        }
        return response()->json(IndiceFinanciero::paginate(($cantidad)));
    }

    public function getIndicadoresData(){
        
        $valoresDB = IndiceFinanciero::select('valorIndicador', 'fechaIndicador')
                                            ->orderBy('fechaIndicador', 'desc')
                                            ->paginate(50);
        $valores = [];
        $fechas = [];
        foreach($valoresDB as $valor){
            array_push($valores,$valor->valorIndicador);
            array_push($fechas, $valor->fechaIndicador);
        }
        $valores = array_reverse($valores);
        $fechas = array_reverse($fechas);
        return response()->json(['valores' => $valores,'fechas' => $fechas]);
    }

    public function getIndicadoresByFecha(Request $request)
    {
        $fechaInicio = $request['fechaInicio'];
        $fechaFinal = $request['fechaFinal'];

        if ($fechaInicio !== null && $fechaFinal !==null){
            $valoresDB = IndiceFinanciero::select('valorIndicador', 'fechaIndicador')
                                            ->where('fechaIndicador', '>=', $fechaInicio)
                                            ->where('fechaIndicador', '<=', $fechaFinal)
                                            ->orderBy('fechaIndicador', 'asc')->get();
        }
        else if ($fechaInicio !== null){
            $valoresDB = IndiceFinanciero::select('valorIndicador', 'fechaIndicador')
                                            ->where('fechaIndicador', '>=', $fechaInicio)
                                            ->orderBy('fechaIndicador', 'asc')->get();
        }
        else if ($fechaFinal !== null){
            $valoresDB = IndiceFinanciero::select('valorIndicador', 'fechaIndicador')
                                            ->where('fechaIndicador', '<=', $fechaFinal)
                                            ->orderBy('fechaIndicador', 'asc')->get();
        }
        $valores = [];
        $fechas = [];
        foreach($valoresDB as $valor){
            array_push($valores,$valor->valorIndicador);
            array_push($fechas, $valor->fechaIndicador);
        }
        return response()->json(['valores' => $valores,'fechas' => $fechas]);
    }

    public function delete($id){
        return IndiceFinanciero::find($id)->delete();
    }

    public function update(Request $request ,$id){
        return response()->json(IndiceFinanciero::find($id)->update($request->all()));
    }

    public function create(Request $request){
        return response()->json(IndiceFinanciero::create($request->all()));
    }

}
