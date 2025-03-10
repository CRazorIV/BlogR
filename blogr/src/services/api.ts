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
    }
};
