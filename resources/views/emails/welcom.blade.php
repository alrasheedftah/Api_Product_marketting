Hell {{$user->name }}

thank you for create an account ,please verify email using this link
{{route('verify',$user->verification_token)}}
