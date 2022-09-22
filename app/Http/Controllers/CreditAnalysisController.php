<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CreditAnalysisController extends Controller
{   
    private $score = 100;
    private $scorePercent = 0;

    /**
     * creditAnalysis
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function creditAnalysis(Request $request)
    {
        //Validacao de campos obrigatorios
        $request->validate([
            'name' => 'required',
            'cpf' => 'required',
            'negative' => 'required',
            'salary' => 'required',
            'limit_card' => 'required',
            'rent_value' => 'required',
            'street' => 'required',
            'street_number' => 'required',
            'county' => 'required',
            'state' => 'required',
            'cep' => 'required',
        ]);

        $response = Http::get('https://viacep.com.br/ws/'.$request->cep.'/json/');
        $responseCep = json_decode($response->body()); 

        if($response->status() != 200)
        {
            return response('Erro ao validar CEP', 400)
                  ->header('Content-Type', 'application/json');
        }

        if(isset($responseCep->erro) || $responseCep->localidade !== $request->county || $responseCep->uf !== $request->state)
        {
            return response('Erro ao validar dados de localização, revise os dados!', 400)
                  ->header('Content-Type', 'application/json');
        }

        $salaryRentDiff = (($request->rent_value * 100) / $request->salary);
        if($salaryRentDiff > 30)
        {
            $this->scorePercent = (18 / 100) * $this->score;
            $this->score = $this->score - $this->scorePercent;
        }        

        if($request->negative == 1)
        {
            $this->scorePercent = (31 / 100) * $this->score;            
            $this->score = $this->score - $this->scorePercent;
        }

        if($request->limit_card <= $request->rent_value)
        {
            $this->scorePercent = (10 / 100) * $this->score;            
            $this->score = $this->score - $this->scorePercent;
        }

        $disapproved90 = true;
        if($disapproved90)
        {
            $this->scorePercent = (15 / 100) * $this->score;            
            $this->score = $this->score - $this->scorePercent;
        }

        if(is_float($this->score))
        {
            $this->score = ceil($this->score);
        }

        return response($this->score, 200);
    }
}