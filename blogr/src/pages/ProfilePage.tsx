import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { BlogPost } from '../types/BlogPost';
import { api } from '../services/api';

interface User {
  id: number;
  name: string;
  email: string;
  avatar: string;
}

interface ProfileData {
  user: User;
  posts: BlogPost[];
  stats: {
    posts_count: number;
    total_likes: number;
    total_comments: number;
  };
}

const ProfilePage: React.FC = () => {
  const { username } = useParams<{ username: string }>();
  const navigate = useNavigate();
  const [profileData, setProfileData] = useState<ProfileData | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentUser, setCurrentUser] = useState<User | null>(null);

  useEffect(() => {
    const userData = localStorage.getItem('user');
    if (userData) {
      setCurrentUser(JSON.parse(userData));
    }
  }, []);

  useEffect(() => {
    const fetchProfileData = async () => {
      try {
        const response = await fetch(`/blogr/api/users/profile.php?username=${username}`);
        if (!response.ok) {
          throw new Error('Failed to fetch profile data');
        }
        const data = await response.json();
        setProfileData(data);
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Failed to load profile');
      } finally {
        setIsLoading(false);
      }
    };

    fetchProfileData();
  }, [username]);

  const handleDeletePost = async (postId: number) => {
    if (!window.confirm('Are you sure you want to delete this post?')) {
      return;
    }

    try {
      await api.deletePost(postId);
      // Refresh profile data
      const response = await fetch(`/blogr/api/users/profile.php?username=${username}`);
      const data = await response.json();
      setProfileData(data);
    } catch (err) {
      setError('Failed to delete post');
    }
  };

  if (isLoading) {
    return <div className="loading">Loading...</div>;
  }

  if (error || !profileData) {
    return <div className="error-message">{error || 'Profile not found'}</div>;
  }

  const isOwnProfile = currentUser?.id === profileData.user.id;

  return (
    <div className="profile-page">
      <div className="profile-header">
        <div className="profile-avatar">
          <img 
            src={profileData.user.avatar || '/default-avatar.png'} 
            alt={`${profileData.user.name}'s avatar`}
          />
        </div>
        <div className="profile-info">
          <h1>{profileData.user.name}</h1>
          <div className="profile-stats">
            <div className="stat">
              <span className="stat-value">{profileData.stats.posts_count}</span>
              <span className="stat-label">posts</span>
            </div>
            <div className="stat">
              <span className="stat-value">{profileData.stats.total_likes}</span>
              <span className="stat-label">likes</span>
            </div>
            <div className="stat">
              <span className="stat-value">{profileData.stats.total_comments}</span>
              <span className="stat-label">comments</span>
            </div>
          </div>
          {isOwnProfile && (
            <button 
              className="btn btn-primary"
              onClick={() => navigate('/settings')}
            >
              Edit Profile
            </button>
          )}
        </div>
      </div>

      <div className="profile-posts">
        <h2>Posts</h2>
        <div className="blog-grid">
          {profileData.posts.map(post => (
            <article key={post.id} className="blog-card">
              {post.featured_image && (
                <img
                  src={post.featured_image}
                  alt={post.title}
                  className="blog-card-image"
                />
              )}
              <div className="blog-card-content">
                <h3 className="blog-card-title">{post.title}</h3>
                <p className="blog-card-excerpt">{post.excerpt}</p>
                <div className="blog-card-actions">
                  <button 
                    className="btn btn-secondary"
                    onClick={() => navigate(`/post/${post.id}`)}
                  >
                    Read More
                  </button>
                  {isOwnProfile && (
                    <>
                      <button 
                        className="btn btn-primary"
                        onClick={() => navigate(`/edit/${post.id}`)}
                      >
                        Edit
                      </button>
                      <button 
                        className="btn btn-danger"
                        onClick={() => handleDeletePost(post.id)}
                      >
                        Delete
                      </button>
                    </>
                  )}
                </div>
              </div>
            </article>
          ))}
        </div>
      </div>
    </div>
  );
};

export default ProfilePage;
