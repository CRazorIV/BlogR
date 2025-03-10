import { BlogPost } from '../types/BlogPost';

interface CreatePostData {
    title: string;
    content: string;
    excerpt: string;
    featured_image?: string;
    author_id: number;
    categories: number[];
}

const API_BASE_URL = '/blogr/api';

export const api = {
    async getAllPosts(): Promise<BlogPost[]> {
        try {
            const response = await fetch(`${API_BASE_URL}/posts/read.php`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return await response.json();
        } catch (error) {
            console.error('Error fetching posts:', error);
            throw error;
        }
    },

    async getPost(id: number): Promise<BlogPost> {
        try {
            const response = await fetch(`${API_BASE_URL}/posts/read_one.php?id=${id}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return await response.json();
        } catch (error) {
            console.error('Error fetching post:', error);
            throw error;
        }
    },

    async createPost(postData: CreatePostData): Promise<BlogPost> {
        try {
            const response = await fetch(`${API_BASE_URL}/posts/create.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(postData),
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return await response.json();
        } catch (error) {
            console.error('Error creating post:', error);
            throw error;
        }
    },

    async deletePost(postId: number): Promise<void> {
        const userData = localStorage.getItem('user');
        if (!userData) {
            throw new Error('User not authenticated');
        }
        const user = JSON.parse(userData);

        try {
            const response = await fetch(`${API_BASE_URL}/posts/delete.php`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    post_id: postId,
                    user_id: user.id
                }),
            });
            if (!response.ok) {
                const data = await response.json();
                throw new Error(data.message || 'Failed to delete post');
            }
        } catch (error) {
            console.error('Error deleting post:', error);
            throw error;
        }
    }
};
