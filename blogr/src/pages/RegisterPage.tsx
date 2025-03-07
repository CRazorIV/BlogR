import { Link } from 'react-router-dom';

const RegisterPage = () => {
  return (
    <div className="register-page">
      <div className="container">
        <div className="auth-form-container">
          <h1>Create an Account</h1>
          <form className="auth-form">
            <div className="form-group">
              <label htmlFor="username">Username</label>
              <input type="text" id="username" name="username" placeholder="Choose a username" />
            </div>
            <div className="form-group">
              <label htmlFor="email">Email</label>
              <input type="email" id="email" name="email" placeholder="Enter your email" />
            </div>
            <div className="form-group">
              <label htmlFor="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Create a password" />
            </div>
            <div className="form-group">
              <label htmlFor="confirmPassword">Confirm Password</label>
              <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" />
            </div>
            <button type="submit" className="btn btn-primary">Register</button>
          </form>
          <p className="auth-redirect">
            Already have an account? <Link to="/login">Login</Link>
          </p>
        </div>
      </div>
    </div>
  );
};

export default RegisterPage;
