<div>
    <h1>Hello {{ $user->username }}</h1>

    <p>
        Please click <a href="{{ $verificationLink }}">this link</a> to complete your registration.<br>
        If you are unable to view or click the link, please copy the link below and paste to your web browser's address bar;<br>
        <code>
            {{ $verificationLink }}
        </code>
    </p>

    <p>
        We are happy to see you joining to our community.<br>
        dwbe Team
    </p>
</div>
