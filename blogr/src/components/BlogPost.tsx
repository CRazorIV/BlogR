import React from 'react';
import { BlogPost as BlogPostType } from '../types/BlogPost';

interface BlogPostProps {
  post: BlogPostType;
  view: 'card' | 'full';
}

export const BlogPost: React.FC<BlogPostProps> = ({ post, view }) => {
  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  if (view === 'card') {
    return (
      <article className="blog-card">
        {post.featured_image && (
          <img
            src={post.featured_image}
            alt={post.title}
            className="blog-card-image"
          />
        )}
        <div className="blog-card-content">
          <h2 className="blog-card-title">{post.title}</h2>
          <p className="blog-card-excerpt">{post.excerpt}</p>
          <div className="blog-post-meta">
            <div className="blog-post-author">
              <img
                src={post.author.avatar}
                alt={post.author.name}
                className="blog-post-author-avatar"
              />
              <span>{post.author.name}</span>
            </div>
            <span className="blog-post-timestamp">
              {formatDate(post.created_at)}
            </span>
          </div>
        </div>
      </article>
    );
  }

  return (
    <article className="blog-post">
      <header className="blog-post-header">
        <h1 className="blog-post-title">{post.title}</h1>
        <div className="blog-post-meta">
          <div className="blog-post-author">
            <img
              src={post.author.avatar}
              alt={post.author.name}
              className="blog-post-author-avatar"
            />
            <span>{post.author.name}</span>
          </div>
          <span className="blog-post-timestamp">
            {formatDate(post.created_at)}
          </span>
        </div>
      </header>

      {post.featured_image && (
        <img
          src={post.featured_image}
          alt={post.title}
          className="blog-post-featured-image"
        />
      )}

      <div 
        className="blog-post-content"
        dangerouslySetInnerHTML={{ __html: post.content }}
      />

      <footer className="blog-post-footer">
        <div className="blog-post-interactions">
          <div className="blog-post-interaction">
            <span>❤️</span>
            <span>{post.likes_count} likes</span>
          </div>
          <div className="blog-post-interaction">
            <span>💬</span>
            <span>{post.comments_count} comments</span>
          </div>
        </div>

        <div className="blog-post-categories">
          {post.categories.map(category => (
            <span key={category.id} className="blog-post-category">
              {category.name}
            </span>
          ))}
        </div>
      </footer>
    </article>
  );
};

export default BlogPost; 