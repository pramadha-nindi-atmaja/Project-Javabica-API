@component('mail::message')
<h2>Hello {{ $name }}</h2>
<p>Thank you for register at javabica.com Use the following OTP to complete your Sign Up procedures. </p>
<br />
<p style="font-size:1.3rem;font-weight:700;letter-spacing: 0.6em; "> {{ $OtpToken }} </p>
<p>OTP is valid until @php echo date('d-m-Y H:i', strtotime($expiredAt)) @endphp</p>
<br/>
<p>Thank you <Br/>Javabica team</p>
@endcomponent