import { useParams } from 'react-router-dom';

const PostDetailPage = () => {
  const { id } = useParams<{ id: string }>();

  return (
    <div className="post-detail-page">
      <div className="container">
        <article className="post">
          <header className="post-header">
            <h1 className="post-title">Post Title Placeholder</h1>
            <div className="post-meta">
              <span className="post-author">By: Author Name</span>
              <span className="post-date">Published: January 1, 2023</span>
            </div>
          </header>
          
          <div className="post-content">
            <p>This is a placeholder for the post content. The actual content for post ID: {id} will be loaded from the API.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nisl nisl aliquam nisl, eget ultricies nisl nisl eget nisl.</p>
          </div>
          
          <footer className="post-footer">
            <div className="post-tags">
              <span className="tag">Tag 1</span>
              <span className="tag">Tag 2</span>
              <span className="tag">Tag 3</span>
            </div>
          </footer>
        </article>
        
        <section className="comments-section">
          <h2>Comments</h2>
          <p>No comments yet.</p>
          {/* Comments will be implemented later */}
        </section>
      </div>
    </div>
  );
};

export default PostDetailPage;
