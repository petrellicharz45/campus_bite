<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class InfoPageController extends Controller
{
    public function privacy(): View
    {
        return $this->renderPage(
            'Privacy Policy',
            'How customer, payment, and delivery information is handled across the store.',
            [
                [
                    'title' => 'Information we collect',
                    'body' => 'We collect only the information required to fulfill food orders, send order updates, confirm payments, and respond to support requests.',
                    'items' => [
                        'Name, phone number, and email address used during account creation or checkout.',
                        'Delivery or pickup location details needed to hand over your order correctly.',
                        'Order history, payment references, and support messages related to your purchases.',
                    ],
                ],
                [
                    'title' => 'How the information is used',
                    'body' => 'Customer information is used to prepare orders, coordinate pickup or delivery, confirm payment status, and improve canteen operations.',
                    'items' => [
                        'To process checkout and issue receipts.',
                        'To communicate about order preparation, delays, delivery, or pickup.',
                        'To investigate payment issues or support questions when needed.',
                    ],
                ],
                [
                    'title' => 'Data sharing and protection',
                    'body' => 'Payment information is handled through approved providers such as Flutterwave. Access to order data is limited to staff who need it to support the service.',
                    'items' => [
                        'We do not sell customer data to third parties.',
                        'Only operational, payment, and support partners involved in the order flow may receive limited relevant information.',
                        'Customers should keep their login credentials private and contact support if they believe their account has been accessed improperly.',
                    ],
                ],
            ]
        );
    }

    public function terms(): View
    {
        return $this->renderPage(
            'Terms of Service',
            'The basic terms that apply when customers browse the menu, place orders, and use the canteen platform.',
            [
                [
                    'title' => 'Using the store',
                    'body' => 'Customers are expected to provide accurate contact and fulfillment details so orders can be prepared and handed over correctly.',
                    'items' => [
                        'Orders should be placed using correct phone, email, and location details.',
                        'Customers are responsible for reviewing the cart, totals, and payment method before checkout.',
                        'Accounts must not be used for fraudulent orders, abusive activity, or payment misuse.',
                    ],
                ],
                [
                    'title' => 'Order acceptance and fulfillment',
                    'body' => 'Orders are confirmed once they are accepted by the platform and may still be affected by stock availability, kitchen delays, or campus delivery conditions.',
                    'items' => [
                        'Product availability may change during busy service periods.',
                        'Estimated preparation and delivery times are guides and can vary during peak hours.',
                        'The business may contact customers to clarify incomplete or unclear delivery instructions.',
                    ],
                ],
                [
                    'title' => 'Payments and cancellations',
                    'body' => 'Payments made online must match the order total, and cash on delivery orders should be ready for collection or handover when the order arrives.',
                    'items' => [
                        'Online payments are verified against the order before final confirmation.',
                        'Customers should request changes or cancellations as early as possible before preparation progresses.',
                        'Repeated failed payments or abusive order behavior may lead to limited account access.',
                    ],
                ],
            ]
        );
    }

    public function refunds(): View
    {
        return $this->renderPage(
            'Refund and Order Policy',
            'What customers can expect if an item is unavailable, incorrect, late, or affected by a payment issue.',
            [
                [
                    'title' => 'When a refund or replacement may apply',
                    'body' => 'The store will review refund or replacement requests fairly when service issues can be verified.',
                    'items' => [
                        'A paid item was not delivered or made available for pickup.',
                        'The wrong meal or an incomplete order was handed over.',
                        'A payment was captured successfully but the order could not be fulfilled.',
                    ],
                ],
                [
                    'title' => 'How requests are reviewed',
                    'body' => 'Support reviews order records, payment confirmation, and any reported issue before deciding whether to replace the meal, issue store credit, or approve a refund.',
                    'items' => [
                        'Requests should be reported as soon as possible after delivery or pickup.',
                        'Customers may be asked for order number, payment reference, and a short explanation of the issue.',
                        'Approved refunds follow the timing and rules of the original payment method.',
                    ],
                ],
                [
                    'title' => 'Situations that may not qualify',
                    'body' => 'Not every complaint results in a refund, especially when preparation has already been completed correctly.',
                    'items' => [
                        'A customer changes their mind after food has already been prepared.',
                        'Incorrect delivery details caused avoidable delay or failed handover.',
                        'Minor presentation differences that do not affect the ordered item materially.',
                    ],
                ],
            ]
        );
    }

    public function hours(): View
    {
        $settings = CompanySetting::query()->first() ?? new CompanySetting(CompanySetting::defaults());
        $hours = collect(preg_split("/\r\n|\n|\r/", (string) $settings->operating_hours))
            ->map(fn (?string $line) => trim((string) $line))
            ->filter()
            ->values()
            ->all();

        return $this->renderPage(
            'Operating Hours',
            'Current service hours for ordering, pickup, and campus food support.',
            [
                [
                    'title' => 'Weekly opening schedule',
                    'body' => 'Ordering hours may vary during holidays, examination periods, or campus-wide events, but the standard weekly schedule is shown below.',
                    'items' => $hours,
                ],
                [
                    'title' => 'Pickup and support guidance',
                    'body' => 'Customers can use the contact options on the site to confirm any same-day changes before placing urgent orders.',
                    'items' => [
                        'Pickup is handled from '.$settings->support_location.'.',
                        'Phone and WhatsApp support are best for urgent order questions during service hours.',
                        'Orders placed close to closing time may be subject to limited menu availability.',
                    ],
                ],
            ]
        );
    }

    /**
     * @param  array<int, array{title: string, body: string, items: array<int, string>}>  $sections
     */
    private function renderPage(string $title, string $intro, array $sections): View
    {
        $settings = CompanySetting::query()->first() ?? new CompanySetting(CompanySetting::defaults());

        return view('pages.info', [
            'pageTitle' => $title,
            'title' => $title,
            'intro' => $intro,
            'sections' => $sections,
            'supportSummary' => [
                'email' => $settings->support_email,
                'phone' => $settings->support_phone,
                'location' => $settings->support_location,
                'hours' => collect(preg_split("/\r\n|\n|\r/", (string) $settings->operating_hours))
                    ->map(fn (?string $line) => trim((string) $line))
                    ->filter()
                    ->map(fn (string $line) => Str::limit($line, 80))
                    ->all(),
            ],
        ]);
    }
}
