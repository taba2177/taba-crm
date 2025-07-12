<?php

namespace Taba\Crm\Livewire;

// use Livewire\Component;
// use Spatie\SchemaOrg\Schema;
// use Illuminate\Support\Facades\Request;
// use Jenssegers\Agent\Agent;

// class Contact extends Component
// {
//     public function mount()
//     {
//         $agent = new Agent();
//         $isMobile = $agent->isMobile();
//         $referrer = Request::server('HTTP_REFERER');

//         // Enhanced detection for Google call button clicks
//         $isGoogleCallButton = $this->detectGoogleCallButton($referrer, $isMobile);

//         if ($isGoogleCallButton) {
//             // Track this conversion if needed
//             // $this->trackCallConversion();

//             // Redirect to primary phone number
//             return redirect()->to('tel:966501248222');
//         }
//     }

//     protected function detectGoogleCallButton($referrer, $isMobile)
//     {
//         if (!$isMobile || !$referrer) {
//             return false;
//         }

//         // Method 1: Check for Google referrer with known call button paths
//         $googleDomains = ['google.com', 'google.co', 'google.ae', 'google.sa'];
//         $isFromGoogle = collect($googleDomains)->contains(fn($domain) => str_contains($referrer, $domain));

//         // Method 2: Check for Google's AMP cache
//         $isFromAmp = str_contains($referrer, 'google.com/amp/');

//         // Method 3: Check for Google's special parameters
//         $hasCallParams = Request::has('_gl') ||
//                          Request::has('_gl_callback') ||
//                          Request::has('gclid');

//         // Method 4: Check user agent for Google-specific patterns
//         $userAgent = Request::header('User-Agent');
//         $isGoogleBot = str_contains($userAgent, 'Googlebot');

//         // Combine detection methods with weights
//         $confidenceScore = 0;
//         if ($isFromGoogle) $confidenceScore += 40;
//         if ($isFromAmp) $confidenceScore += 30;
//         if ($hasCallParams) $confidenceScore += 20;
//         if ($isGoogleBot) $confidenceScore += 10;

//         // Consider it a call button click if score > 50
//         return $confidenceScore > 50;
//     }

//     public function render()
//     {
//         $this->setSeoMetadata();
//         return view('livewire.contact');
//     }

//     protected function setSeoMetadata()
//     {
//         seo()
//             ->title('اتصل بنا - ستائر المودرن | صن رول للستائر')
//             ->description('تواصل معنا لطلب ستائر رول وامريكي - استشارة مجانية لاختيار الستائر المناسبة لمنزلك او مكتبك - خدمة عملاء على مدار الساعة')
//             ->canonical(route('contact'))
//             ->addSchema(
//                 Schema::contactPage()
//                     ->name('اتصل بنا - صن رول للستائر والكنب')
//                     ->description('تواصل معنا لطلب ستائر رول وامريكي - استشارة مجانية لاختيار الستائر المناسبة لمنزلك او مكتبك - خدمة عملاء على مدار الساعة')
//                     ->url(route('contact'))
//                     ->telephone('+966501248222')
//                     ->potentialAction(
//                         Schema::communicateAction()
//                             ->actionOption('http://schema.org/ContactPoint')
//                             ->url('tel:+966501248222')
//                     )
//                     ->author(Schema::organization()->name(config('app.name')))
//             ->addMeta([
//                 'name' => 'robots',
//                 'content' => 'index, follow'
//             ]););
//     }
// }

use Livewire\Component;
use Spatie\SchemaOrg\Schema;
use Illuminate\Support\Facades\Request;


class Contact extends Component
{

    // public function mount()
    // {
    //     $referer = Request::server('HTTP_REFERER');
    //     $isFromGoogle = $referer && str_contains($referer, 'google.com');
    //     $hasCallback = Request::has('_gl_callback');

    //     // Only redirect if from Google AND has the _gl_callback param
    //     if ($isFromGoogle && $hasCallback) {
    //         redirect()->to('tel:966501248222')->send();
    //         exit; // ensure redirect happens immediately before rendering
    //     }
    // }

    public function render()
    {

        $this->setSeoMetadata();

        return view('livewire.contact');
    }

    protected function setSeoMetadata()

    {
        seo()
            ->title('اتصل بنا - ستائر المودرن | صن رول للستائر')
            ->description('تواصل معنا لطلب ستائر رول وامريكي - استشارة مجانية لاختيار الستائر المناسبة لمنزلك او مكتبك - خدمة عملاء على مدار الساعة')
            ->canonical(route('contact'))
            ->addSchema(
            Schema::contactPage()
                ->name('اتصل بنا - صن رول للستائر والكنب')
                ->description('تواصل معنا لطلب ستائر رول وامريكي - استشارة مجانية لاختيار الستائر المناسبة لمنزلك او مكتبك - خدمة عملاء على مدار الساعة')
                ->url(route('contact'))
                ->author(Schema::organization()->name(config('app.name')))
            );
    }

}
