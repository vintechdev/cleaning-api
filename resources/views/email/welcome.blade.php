@include('email.header_new')

<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
    <tr>
        <td style="padding-left: 5px; padding-right: 5px;">
            <h3 style="font-size: 22px; font-weight: bold; color: #52b68d; margin-bottom: 12px; text-align: center;">
                Kaushik, welcome to Cleaning!
            </h3>
            <p style="font-size: 16px; color: #718096; text-align: left;">With your email <span style="font-weight: bold; color: #3d4852;">thakkar.kaushik@gmail.com</span> and password, you can now log in to your Cleaning account to manage your bookings.</p>
            @component('email.components.button', ['url' => '/'])
                Login to my account
            @endcomponent
        </td>
    </tr>
    <tr>
        <td style="height: 20px;"></td>
    </tr>
</table>

<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
    <tr>
        <td style="min-width: 100px; text-align: center; vertical-align: top; background-color: #e0f7ed; padding-top: 15px;">
            <img src="{{asset('/images/email/checklist.png') }}" alt="Bookings" width="50" style="border: 0;">
        </td>
        <td style="padding-right: 10px; background-color: #e0f7ed; padding-top: 15px;">
            <h4 style="font-weight: bold; color: #52b68d; margin-top: 0; margin-bottom: 0;">Manage all your bookings</h4>
            <p style="font-size: 16px; color: #718096; margin-top: 5px; margin-bottom: 5px;">Reschedule, rebook and view your past, present and future appointments in your profile.</p>
            @component('email.components.button', ['url' => '/', 'align' => 'left', 'color' => 'secondary', 'space' => '10px'])
                My Bookings
            @endcomponent
        </td>
    </tr>

    <tr>
        <td style="min-width: 100px; text-align: center; vertical-align: top; padding-top: 15px;">
            <img src="{{asset('/images/email/laptop.png') }}" alt="Laptop" width="60" style="border: 0;">
        </td>
        <td style="padding-right: 10px; padding-top: 15px;">
            <h4 style="font-weight: bold; color: #52b68d; margin-top: 0; margin-bottom: 0;">Check your account details</h4>
            <p style="font-size: 16px; color: #718096; margin-top: 5px; margin-bottom: 5px;">Make sure that all information, including your address, contact and payment details are correct.</p>
            @component('email.components.button', ['url' => '/', 'align' => 'left', 'color' => 'secondary', 'space' => '10px'])
                My Details
            @endcomponent
        </td>
    </tr>

    <tr>
        <td style="min-width: 100px; text-align: center; vertical-align: top; background-color: #e0f7ed; padding-top: 15px;">
            <img src="{{asset('/images/email/contacts.png') }}" alt="Contact" width="60" style="border: 0;">
        </td>
        <td style="padding-right: 10px; background-color: #e0f7ed; padding-top: 15px;">
            <h4 style="font-weight: bold; color: #52b68d; margin-top: 0; margin-bottom: 0;">Get in touch with your cleaner</h4>
            <p style="font-size: 16px; color: #718096; margin-top: 5px; margin-bottom: 5px;">Get to know them and discuss what areas you would like them to focus on, so that they arrive prepared.</p>
            @component('email.components.button', ['url' => '/', 'align' => 'left', 'color' => 'secondary', 'space' => '10px'])
                Contact
            @endcomponent
        </td>
    </tr>
</table>

<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
    <tr>
        <td style="height: 20px;" colspan="2"></td>
    </tr>
    <tr>
        <td colspan="2">
            <h3 style="font-size: 18px; font-weight: bold; color: #52b68d; margin-bottom: 12px; text-align: center;">
                Benefits of your online profile
            </h3>
        </td>
    </tr>
    <tr>
        <td style="width: 80px; padding: 10px; text-align: center; border-bottom: 1px solid #eee;">
            <img src="{{asset('/images/email/broom.png') }}" alt="Cleaners" width="40" style="border: 0;">
        </td>
        <td style="padding: 10px 5px; border-bottom: 1px solid #eee;">
            <h4 style="font-size: 15px; font-weight: bold; color: #3d4852; margin-top: 0; margin-bottom: 0;">
                Cleaners near you
            </h4>
            <p style="font-size: 14px; color: #718096; margin-top: 0; margin-bottom: 0;">Get highly rated cleaners in your area.</p>
        </td>
    </tr>
    <tr>
        <td style="height: 5px;" colspan="2"></td>
    </tr>
    <tr>
        <td style="width: 80px; padding: 10px; text-align: center; border-bottom: 1px solid #eee;">
            <img src="{{asset('/images/email/calendar.png') }}" alt="Reschedule" width="40" style="border: 0;">
        </td>
        <td style="padding: 10px 5px; border-bottom: 1px solid #eee;">
            <h4 style="font-size: 15px; font-weight: bold; color: #3d4852; margin-top: 0; margin-bottom: 0;">
                Going on Holiday
            </h4>
            <p style="font-size: 14px; color: #718096; margin-top: 0; margin-bottom: 0;">Easily reschedule or cancel a clean from your user account.</p>
        </td>
    </tr>
    <tr>
        <td style="height: 5px;" colspan="2"></td>
    </tr>
    <tr>
        <td style="width: 80px; padding: 10px; text-align: center;">
            <img src="{{asset('/images/email/speech-bubble.png') }}" alt="Reschedule" width="40" style="border: 0;">
        </td>
        <td style="padding: 10px 5px;">
            <h4 style="font-size: 15px; font-weight: bold; color: #3d4852; margin-top: 0; margin-bottom: 0;">
                Contact your cleaner
            </h4>
            <p style="font-size: 14px; color: #718096; margin-top: 0; margin-bottom: 0;">Communicate with your provider using our chat function.</p>
        </td>
    </tr>
    <tr>
        <td style="height: 25px;"></td>
    </tr>
</table>

@include('email.footer_new')
