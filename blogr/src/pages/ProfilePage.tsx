import { useParams } from 'react-router-dom';

const ProfilePage = () => {
  const { username } = useParams<{ username: string }>();

  return (
    <div className="profile-page">
      <div className="container">
        <div className="profile-header">
          <div className="profile-avatar">
            {/* Placeholder for avatar */}
            <div className="avatar-placeholder"></div>
          </div>
          <div className="profile-info">
            <h1>{username}'s Profile</h1>
            <p className="profile-bio">This is a bio placeholder. User bio will be displayed here.</p>
            <div className="profile-stats">
              <div className="stat">
                <span className="stat-value">0</span>
                <span className="stat-label">Posts</span>
              </div>
              <div className="stat">
                <span className="stat-value">0</span>
                <span className="stat-label">Followers</span>
              </div>
              <div className="stat">
                <span className="stat-value">0</span>
                <span className="stat-label">Following</span>
              </div>
            </div>
          </div>
        </div>

        <div className="profile-content">
          <h2>Posts</h2>
          <p>No posts yet.</p>
          {/* Post list will be implemented later */}
        </div>
      </div>
    </div>
  );
};

export default ProfilePage;
