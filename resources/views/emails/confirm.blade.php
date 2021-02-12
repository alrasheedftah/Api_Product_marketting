Hello {{$user->name}}
your changed email ,please use this link to verify email :
{{route('verify',$user->verification_token)}}
