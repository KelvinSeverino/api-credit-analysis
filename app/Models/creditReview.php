<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'cpf', 'negative', 'salary', 'limit_card', 'rent_value', 'street', 'street_number', 'county', 'state', 'cep', 'final_score', 'result'
    ];

    private $score = 100;
    private $scorePercent = 0;

    private $negative = null;
    private $salary = 0;
    private $rentValue = 0;
    private $limitCard = 0;

    private $rentAboveSalary = false;
    private $negativeProfile = false;    
    private $resultDescription = null;
    
    /**
     * analyzeCreditData - Realiza todas as analises do perfil do cliente
     *
     * @param  string $cpf
     * @param  float $salary
     * @param  float $limitCard
     * @param  float $rentValue
     * @param  int $negative
     * @return object
     */
    public function analyzeCreditData($cpf, $salary, $limitCard, $rentValue, $negative): object
    {
        $this->salary = $salary;
        $this->limitCard = $limitCard;
        $this->rentValue = $rentValue;
        $this->negative = $negative;

        $salaryRentDiff = (($this->rentValue * 100) / $this->salary);
        if($salaryRentDiff > 30)
        {
            $this->scorePercent = (18 / 100) * $this->score;
            $this->score = $this->score - $this->scorePercent;
            $this->rentAboveSalary = true;
        }        

        if($this->negative == 1)
        {
            $this->scorePercent = (31 / 100) * $this->score;            
            $this->score = $this->score - $this->scorePercent;
            $this->negativeProfile = true;
        }

        if($this->limitCard <= $this->rentValue)
        {
            $this->scorePercent = (10 / 100) * $this->score;            
            $this->score = $this->score - $this->scorePercent;
        }

        if($this->searchPreviousDisapproval() > 0)
        {
            $this->scorePercent = (15 / 100) * $this->score;            
            $this->score = $this->score - $this->scorePercent;
        }

        if(is_float($this->score))
        {
            $this->score = ceil($this->score);
        }

        $this->checkResult($this->rentAboveSalary, $this->negative);

        return (object)[
            "score" => $this->score, 
            "resultDescription" => $this->resultDescription
        ];
    }
    
    /**
     * checkResult - Verifica os resultados de analisa para gerar o Status
     *
     * @return string
     */
    private function checkResult(): string
    {        
        if($this->rentAboveSalary && $this->negativeProfile || $this->score <= 30)
        {
            return $this->resultDescription = 'Reprovado';
        }

        if($this->score > 30 && $this->score < 60)
        {
            return $this->resultDescription = 'Derivado';
        } 
        else 
        {
            return $this->resultDescription = 'Aprovado';
        }
    }
    
    /**
     * searchPreviousDisapproval - Busca por reprovações nos ultimos 90 dias
     *
     * @return int
     */
    private function searchPreviousDisapproval(): int
    {
        return self::where("created_at",">=", Carbon::now()->subMonths(3))->where("result", "Reprovado")->get()->count();
    }
}
