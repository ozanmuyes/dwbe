<div>
    <h1>Hello {{ $user->username }}</h1>

    <p>
        Administrator "{{ $referenceUser->username }}" added you as administrator.
    </p>

    <p>
        Please click <a href="{{ $passwordSetLink }}">this link</a> to set your password.<br>
        If you are unable to view or click the link, please copy the link below and paste to your web browser's address bar;<br>
        <code>
            {{ $passwordSetLink }}
        </code>
    </p>

    <p>
        We are happy to see you joining to our community.<br>
        dwbe Team
    </p>
</div>
