import { Link } from 'react-router-dom';

const LoginPage = () => {
  return (
    <div className="login-page">
      <div className="container">
        <div className="auth-form-container">
          <h1>Login to BlogR</h1>
          <form className="auth-form">
            <div className="form-group">
              <label htmlFor="email">Email</label>
              <input type="email" id="email" name="email" placeholder="Enter your email" />
            </div>
            <div className="form-group">
              <label htmlFor="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Enter your password" />
            </div>
            <button type="submit" className="btn btn-primary">Login</button>
          </form>
          <p className="auth-redirect">
            Don't have an account? <Link to="/register">Register</Link>
          </p>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;
