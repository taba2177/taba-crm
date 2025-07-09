<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class RequestQuoteForm extends Component
{
    // Personal Details
    public $name;
    public $phone;
    public $district;
    public $notes;

    // Curtain Details
    public $width;
    public $height;
    public $curtain_type;
    public $room_name;

    // reCAPTCHA Token
    public $quoteRecaptchaToken;
    public $franchiseRecaptchaToken;


    // Validation Rules
    protected $rules = [
        'name' => 'required|string|min:3|regex:/^[\p{Arabic}a-zA-Z ]+$/u', // Arabic and English letters only
        'phone' => 'required|string|min:10|max:10|regex:/^[0-9]+$/', // 10 digits only
        'quoteRecaptchaToken' => 'required',
        // 'district' => 'required|string',
        // 'notes' => 'nullable|string',
        // 'width' => 'required|numeric|min:1',
        // 'height' => 'required|numeric|min:1',
        // 'curtain_type' => 'required|string',
        // 'room_name' => 'required|string',
    ];

    // Custom Validation Messages
    protected $messages = [
        'name.required' => 'الاسم مطلوب.',
        'name.regex' => 'الرجاء إدخال اسم صالح يحتوي فقط على حروف.',
        'phone.required' => 'رقم الجوال مطلوب.',
        'phone.regex' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام.',
        'quoteRecaptchaToken.required' => 'التحقق من reCAPTCHA مطلوب.',

        // 'district.required' => 'الحي مطلوب.',
        // 'width.required' => 'العرض مطلوب.',
        // 'height.required' => 'الارتفاع مطلوب.',
        // 'curtain_type.required' => 'نوع الستارة مطلوب.',
        // 'room_name.required' => 'اسم الغرفة مطلوب.',
    ];
    public function triggerRecaptcha()
    {
        $this->dispatch('triggerQuoteRecaptcha', [
            'formId' => 'quoteForm',
            'tokenProperty' => 'quoteRecaptchaToken'
        ]);
    }
    // Real-Time Validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // Handle Form Submission
    public function submit()
    {
        if (!$this->quoteRecaptchaToken) {
            $this->triggerRecaptcha();
            session()->flash('error', 'يرجى الانتظار حتى يتم التحقق من reCAPTCHA.');
            return;
        }
        // Validate the form data
        $this->validate();

        // Verify reCAPTCHA token
        $recaptchaResponse = $this->verifyRecaptcha($this->quoteRecaptchaToken);

        // if (!$recaptchaResponse['success']) {
        //     // reCAPTCHA verification failed
        //     $errorCodes = $recaptchaResponse['error-codes'];
        //     // \Log::error('reCAPTCHA verification failed: ' );
        //     session()->flash('error', 'فشل التحقق من reCAPTCHA. يرجى المحاولة مرة أخرى.' . implode(', ', $errorCodes));
        //     return;
        // }
        // Prepare data for submission
        
        $formData = [
            'entry.2036097969' => e($this->name),
            'entry.1147644131' => e($this->phone),
            'entry.157362552' => e($this->district),
            'entry.957872669' => e($this->notes),
            'entry.603436746' => e($this->width),
            'entry.735917943' => e($this->height),
            'entry.1241205268' => e($this->curtain_type),
            'entry.345085267' => e($this->room_name),
        ];

        // Send data to Google Form
        $googleFormUrl = 'https://docs.google.com/forms/d/1d3YIq07UoWE2pMTAAhg-gJH_MgFPLb3NS4dfb8mgNBw/formResponse';

        // https://docs.google.com/forms/d/e/1FAIpQLSdYLElV4CXpsnGAh1ZOJnAMmiN2LPCUZZT-HvXlY2eYbohIJg/viewform?usp=dialog
        $queryString = http_build_query($formData);

        // Use Livewire's HTTP client to send the request
        $response = Http::get($googleFormUrl . '?' . $queryString);

        if ($response->successful()) {
            session()->flash('message', 'تم إرسال البيانات بنجاح!');
        } else {
            session()->flash('error', 'حدث خطأ أثناء إرسال البيانات. يرجى المحاولة مرة أخرى.');
            // Log the error for debugging
            \Log::error('Google Form submission failed: ' . $response->body());
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
        $this->dispatch('resetRecaptcha', 'quote');
    }
    // Render the Livewire component view
    public function render()
    {
        return view('livewire.request-quote-form');
    }
}