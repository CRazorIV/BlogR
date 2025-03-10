export interface BlogPost {
    id: number;
    title: string;
    content: string;
    featured_image: string;
    author: {
        id: number;
        name: string;
        avatar: string;
    };
    created_at: string;
    updated_at: string;
    categories: Array<{
        id: number;
        name: string;
    }>;
    likes_count: number;
    comments_count: number;
    excerpt: string;
} 