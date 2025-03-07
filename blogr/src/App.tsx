import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { lazy, Suspense } from 'react';

// Layout components
import Header from './components/layout/Header';
import Footer from './components/layout/Footer';

// Pages
const HomePage = lazy(() => import('./pages/HomePage'));
const LoginPage = lazy(() => import('./pages/LoginPage'));
const RegisterPage = lazy(() => import('./pages/RegisterPage'));
const ProfilePage = lazy(() => import('./pages/ProfilePage'));
const PostDetailPage = lazy(() => import('./pages/PostDetailPage'));
const MessagePage = lazy(() => import('./pages/MessagePage'));
const NotFoundPage = lazy(() => import('./pages/NotFoundPage'));

// Loading component
const LoadingFallback = () => <div className="loading">Loading...</div>;

function App() {
  // We'll replace this with actual auth state from context later
  const isAuthenticated = false;

  return (
    <Router>
      <div className="app-container">
        <Header />
        <main className="main-content">
          <Suspense fallback={<LoadingFallback />}>
            <Routes>
              {/* Public routes */}
              <Route path="/" element={<HomePage />} />
              <Route path="/login" element={
                isAuthenticated ? <Navigate to="/" /> : <LoginPage />
              } />
              <Route path="/register" element={
                isAuthenticated ? <Navigate to="/" /> : <RegisterPage />
              } />
              <Route path="/post/:id" element={<PostDetailPage />} />

              {/* Protected routes */}
              <Route path="/profile/:username" element={
                isAuthenticated ? <ProfilePage /> : <Navigate to="/login" />
              } />
              <Route path="/messages" element={
                isAuthenticated ? <MessagePage /> : <Navigate to="/login" />
              } />

              {/* 404 route */}
              <Route path="*" element={<NotFoundPage />} />
            </Routes>
          </Suspense>
        </main>
        <Footer />
      </div>
    </Router>
  );
}

export default App;
