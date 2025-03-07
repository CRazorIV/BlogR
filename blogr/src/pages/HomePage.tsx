const HomePage = () => {
  return (
    <div className="home-page">
      <div className="container">
        <h1>Welcome to BlogR</h1>
        <p>Discover stories, thinking, and expertise from writers on any topic.</p>
        
        <div className="posts-container">
          <h2>Recent Posts</h2>
          <p>Loading posts...</p>
          {/* Post list will be implemented later */}
        </div>
      </div>
    </div>
  );
};

export default HomePage;
