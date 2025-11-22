<form action="{{ route('admin.login.submit') }}" method="POST">
    @csrf
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
