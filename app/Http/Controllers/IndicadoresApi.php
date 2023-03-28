<?php

namespace App\Http\Controllers;

use App\Models\IndiceFinanciero;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class IndicadoresApi extends Controller
{
    protected function getToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://postulaciones.solutoria.cl/api/acceso',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "userName": "joseivecasxnxun_ndn@indeedemail.com",
                "flagJson": true
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response)->token;
    }

    protected function getLista()
    {
        $curl = curl_init();
        $token = IndicadoresApi::getToken();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://postulaciones.solutoria.cl/api/indicadores',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token"
              ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    public function update()
    {
        IndiceFinanciero::truncate();
        $lista = IndicadoresApi::getLista();
        foreach($lista as $indicador)
        {
            if ($indicador['codigoIndicador'] !== 'UF'){
                continue;
            }
            unset($indicador['id']);
            IndiceFinanciero::create($indicador);

            // En vez de eliminar todas las filas la idea era actualizar los datos existentes y crear los
            // que no existieran.

            // if(IndiceFinanciero::where('id',$indicador["id"])->exists()) {
            //     $previousIndicador = IndiceFinanciero::find($indicador["id"]);
            //     unset($indicador['id']);
            //     $previousIndicador->update($indicador);
            // }
            // else{
            //     unset($indicador['id']);
            //     IndiceFinanciero::create($indicador);
            // }
            
        }
        return redirect(route('indices.index'));
    }
}
