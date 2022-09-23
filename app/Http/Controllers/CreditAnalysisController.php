<?php

namespace App\Http\Controllers;

use App\Models\CreditReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CreditAnalysisController extends Controller
{   
    /**
     * creditAnalysis
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showAnalysis(Request $request)
    {
        $request->validate([
            'cpf' => 'required',
        ]);

        if(!is_CPF($request->cpf))
        {
            return response('CPF Inválido!', 200)
                  ->header('Content-Type', 'application/json');
        }

        return response()->json(CreditReview::where('cpf', $request->cpf)->orderBy('id','desc')->take(1)->get());
    }

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

        if(!is_CPF($request->cpf))
        {
            return response('CPF Inválido!', 200)
                  ->header('Content-Type', 'application/json');
        }

        $creditReview = new CreditReview();
        $resultCreditReview = $creditReview->analyzeCreditData($request->cpf, $request->salary, $request->limit_card, $request->rent_value, $request->negative);

        $creditReviewCreate = CreditReview::create([
            'name' => $request->name,
            'cpf' => $request->cpf,
            'negative' => $request->negative,
            'salary' => $request->salary,
            'limit_card' => $request->limit_card,
            'rent_value' => $request->rent_value,
            'street' => $request->street,
            'street_number' => $request->street_number,
            'county' => $request->county,
            'state' => $request->state,
            'cep' => $request->cep,
            'final_score' => $resultCreditReview->score,
            'result' => $resultCreditReview->resultDescription
        ]);

        return response()->json([
            'cod_ref' => $creditReviewCreate->id,
            'score' => $resultCreditReview->score,
            'result' => $resultCreditReview->resultDescription
        ]);
    }
}