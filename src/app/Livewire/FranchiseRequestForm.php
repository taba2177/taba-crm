<?php

namespace Taba\Crm\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Spatie\SchemaOrg\Schema;


class FranchiseRequestForm extends Component
{
    // Form Fields
    public $full_name;
    public $age;
    public $gender;
    public $education;
    public $city;
    public $branches;
    public $phone2;
    public $email;
    public $country;
    public $province;
    public $business_experience;
    public $restaurant_experience;
    public $investment_amount;
    public $has_loans;
    public $franchiseRecaptchaToken;

    // Validation Rules
    protected $rules = [
        'full_name' => 'required|string|min:3|regex:/^[\p{Arabic}a-zA-Z ]+$/u',
        // 'age' => 'required|numeric|min:18|max:80',
        // 'gender' => 'required|in:male,female',
        // 'education' => 'required|string|min:2',
        // 'city' => 'required|string|min:3',
        // 'branches' => 'required|numeric|min:1',
        'phone2' => 'required|string|regex:/^\+?[0-9]{9,15}$/',
        // 'email' => 'required|email',
        // 'country' => 'required|in:saudi,uae,egypt',
        // 'province' => 'required|string|min:3',
        // 'business_experience' => 'nullable|string|max:500',
        // 'restaurant_experience' => 'nullable|string|max:500',
        // 'investment_amount' => 'required|string',
        // 'has_loans' => 'required|in:yes,no',
        'franchiseRecaptchaToken' => 'required',
    ];

    // Validation Messages
    protected $messages = [
        'full_name.required' => 'الاسم الثلاثي مطلوب',
        // 'age.required' => 'العمر مطلوب',
        // 'gender.required' => 'الجنس مطلوب',
        // 'education.required' => 'المؤهل الدراسي مطلوب',
        // 'city.required' => 'المدينة مطلوبة',
        // 'branches.required' => 'عدد الوحدات المطلوبة مطلوب',
        'phone2.required' => 'رقم الهاتف مطلوب',
        // 'email.required' => 'البريد الإلكتروني مطلوب',
        // 'country.required' => 'البلد مطلوب',
        // 'province.required' => 'المحافظة مطلوبة',
        // 'investment_amount.required' => 'مبلغ الاستثمار مطلوب',
        // 'has_loans.required' => 'حالة القروض مطلوبة',
        'franchiseRecaptchaToken.required' => 'التحقق من reCAPTCHA مطلوب',
    ];

    public function triggerRecaptcha()
    {
        $this->dispatch('triggerFranchiseRecaptcha', [
            'formId' => 'franchiseForm',
            'tokenProperty' => 'franchiseRecaptchaToken'
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        $this->validate();

        if (!$this->franchiseRecaptchaToken) {
            $this->triggerRecaptcha();
            session()->flash('franchise.error', 'يرجى الانتظار حتى يتم التحقق من reCAPTCHA.');
            return;
        }


        $recaptchaResponse = $this->verifyRecaptcha($this->franchiseRecaptchaToken);


        // if (!$recaptchaResponse['success']) {
        //     // reCAPTCHA verification failed
        //     $errorCodes = $recaptchaResponse['error-codes'];
        //     // \Log::error('reCAPTCHA verification failed: ' );
        //     session()->flash('error', 'فشل التحقق من reCAPTCHA. يرجى المحاولة مرة أخرى.' . implode(', ', $errorCodes));
        //     return;
        // }

        $formData = [
            'entry.1228685355' => e($this->full_name),
            'entry.380659064' => e($this->age),
            'entry.1527769998' => e($this->gender),
            'entry.863069383' => e($this->education),
            'entry.1665122121' => e($this->city),
            'entry.1452159329' => e($this->branches),
            'entry.1234204652' => e($this->phone2),
            'entry.1284279715' => e($this->email),
            'entry.793331899' => e($this->country),
            'entry.768435483' => e($this->province),
            'entry.1665751215' => e($this->business_experience),
            'entry.92993363' => e($this->restaurant_experience),
            'entry.1129002075' => e($this->investment_amount),
            'entry.410393574' => e($this->has_loans),
        ];

        $googleFormUrl = 'https://docs.google.com/forms/d/1E-EilPVgYfu_LiBpTi3jGBtYrPAwkrRvg0f3wfq3cRk/formResponse';
        $response = Http::asForm()->post($googleFormUrl, $formData);

        if ($response->successful()) {
            session()->flash('franchise.message', 'تم إرسال طلبك بنجاح! سنتواصل معك قريباً.');
        } else {
            session()->flash('franchise.error', 'حدث خطأ أثناء الإرسال. يرجى المحاولة مرة أخرى.');
        }

        $this->resetForm();
    }

    protected function verifyRecaptcha($token)
    {
        $secretKey = config('services.recaptcha.secret_key'); // Store your Secret Key in .env
        $response = Http::post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
        ]);

        return $response->json();
    }

    private function resetForm()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('resetRecaptcha', 'franchise');
    }
    public function render()
    {
        $this->setSeoMetadata();

        return view('livewire.franchise-request-form');

   }

    protected function setSeoMetadata()
    {
        seo()
            ->title('صن رول للستائر والكنب | امتياز تجاري من شركة صن رول للستائر')
            ->description('انضم إلى عائلة صن رول للستائر واحصل على امتياز تجاري مميز. فرص استثمارية رائدة في مجال الستائر ')
            ->canonical(route('franchise-request'))
            ->addSchema(
            Schema::webPage()
                ->name('الامتياز التجاري صن رول للستائر والكنب')
                ->description('فرص امتياز تجاري مميزة مع صن رول للستائر - استثمر في مجال الستائر مع علامة تجارية رائدة في المملكة العربية السعودية')
                ->url(route('franchise-request'))
                ->author(Schema::organization()->name(config('app.name')))
            );
    }
}
