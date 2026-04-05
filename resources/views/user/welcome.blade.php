@extends('layouts.pulse')

@section('content')
            @if(isset($trendingPosts) && $trendingPosts->count() > 0)
            <div class="trending">
                <div class="trending-carousel-wrapper">
                    <button class="carousel-btn prev" onclick="moveCarousel(-1)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    
                    <div class="trending-viewport">
                        <div class="trending-track" id="carouselTrack">
                            @foreach($trendingPosts as $tPost)
                            <div class="trending-card-reddit" onclick="window.location='{{ route('posts.show', $tPost->id) }}'">
                                <div class="bg-image" style="background-image: url('{{ filter_var($tPost->image_path, FILTER_VALIDATE_URL) ? $tPost->image_path : asset('storage/' . $tPost->image_path) }}')"></div>
                                <div class="overlay">
                                    <h3 class="title">{{ $tPost->title }}</h3>
                                    <p class="desc">{{ Str::limit(strip_tags($tPost->content), 60) }}</p>
                                    <div class="footer">
                                        <img src="{{ $tPost->user->profile && $tPost->user->profile->avatar_path ? asset('storage/' . $tPost->user->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($tPost->user->username ?? $tPost->user->name) }}" alt="avatar" style="border: 2px solid white;">
                                        <span>p/{{ strtolower($tPost->category->label ?? 'general') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <button class="carousel-btn next" onclick="moveCarousel(1)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>

                    <div class="carousel-dots" id="carouselDots">
                        @for($i = 0; $i < ceil($trendingPosts->count() / 1); $i++)
                            <div class="dot {{ $i == 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})"></div>
                        @endfor
                    </div>
                </div>
            </div>
            @endif

            <script>
                function removePreview() {
                    document.getElementById('modal-file').value = "";
                    document.getElementById('image-preview-container').style.display = 'none';
                }

                let currentSlide = 0;
                const track = document.getElementById('carouselTrack');
                const dots = document.querySelectorAll('.dot');
                const totalItems = {{ isset($trendingPosts) ? min($trendingPosts->count(), 6) : 0 }};
                
                function getVisibleCount() {
                    if (window.innerWidth > 1200) return 3;
                    if (window.innerWidth > 768) return 2;
                    return 1;
                }

                function updateCarousel() {
                    if (!track) return;
                    const visible = getVisibleCount();
                    const maxSlide = Math.max(0, totalItems - visible);
                    if (currentSlide > maxSlide) currentSlide = 0;
                    if (currentSlide < 0) currentSlide = maxSlide;

                    const cardWidth = track.firstElementChild ? track.firstElementChild.offsetWidth : 0;
                    const gap = 20;
                    track.style.transform = `translateX(-${currentSlide * (cardWidth + gap)}px)`;
                    
                    dots.forEach((dot, index) => {
                        dot.classList.toggle('active', index === currentSlide);
                    });
                }

                function moveCarousel(direction) {
                    currentSlide += direction;
                    updateCarousel();
                }

                function goToSlide(index) {
                    currentSlide = index;
                    updateCarousel();
                }

                if (totalItems > 0) {
                    setInterval(() => {
                        currentSlide = (currentSlide + 1) % totalItems;
                        updateCarousel();
                    }, 5000);
                }

                window.addEventListener('resize', updateCarousel);
                document.addEventListener('DOMContentLoaded', updateCarousel);
            </script>

            <style>
                .new-post-cta {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 20px;
                    border-radius: var(--radius-pill);
                    background: rgba(124, 58, 237, 0.08);
                    color: var(--accent-primary);
                    font-weight: 800;
                    font-size: 14px;
                    border: 1.5px solid rgba(124, 58, 237, 0.2);
                    cursor: pointer;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    margin-left: 12px;
                    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.1);
                }

                .new-post-cta:hover {
                    background: var(--accent-gradient);
                    color: white;
                    border-color: transparent;
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(124, 58, 237, 0.3);
                }

                .new-post-cta:active {
                    transform: translateY(0) scale(0.96);
                    filter: brightness(0.95);
                }

                .new-post-cta svg {
                    transition: transform 0.4s ease;
                }

                .new-post-cta:hover svg {
                    transform: rotate(90deg);
                }
            </style>

            <div class="feed-controls" style="border: none; padding-bottom: 24px; display: flex; align-items: center; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.03); padding: 4px; border-radius: var(--radius-pill); border: 1px solid var(--border-glass);">
                    @if(isset($sort) && $sort == 'new')
                        <span class="feed-tab" style="background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.08); color: var(--text-primary);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Latest
                        </span>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'new']) }}" class="feed-tab" style="text-decoration: none;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Latest
                        </a>
                    @endif

                    @auth
                        @if(isset($sort) && $sort == 'following')
                            <div style="display: inline-flex; align-items: center; gap: 4px; padding: 7px 8px 7px 14px; border-radius: var(--radius-pill); background: var(--accent-gradient); font-size: 14px; font-weight: 600; color: white; white-space: nowrap;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                                For You
                                <a href="{{ route('home') }}" title="Reset to global default"
                                   style="display: flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; background: rgba(255,255,255,0.25); color: white; margin-left: 2px; transition: background 0.2s ease;"
                                   onmouseover="this.style.background='rgba(255,255,255,0.45)'"
                                   onmouseout="this.style.background='rgba(255,255,255,0.25)'">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </a>
                            </div>
                        @else
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'following']) }}" class="feed-tab" style="text-decoration: none;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                                For You
                            </a>
                        @endif
                    @endauth

                    @if(isset($sort) && $sort == 'hot')
                        <div style="display: inline-flex; align-items: center; gap: 4px; padding: 7px 8px 7px 14px; border-radius: var(--radius-pill); background: var(--accent-gradient); font-size: 14px; font-weight: 600; color: white; white-space: nowrap;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                            Hot
                            <a href="{{ route('home') }}" title="Reset"
                               style="display: flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; background: rgba(255,255,255,0.25); color: white; margin-left: 2px; transition: background 0.2s ease;"
                               onmouseover="this.style.background='rgba(255,255,255,0.45)'"
                               onmouseout="this.style.background='rgba(255,255,255,0.25)'">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </a>
                        </div>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'hot']) }}" class="feed-tab" style="text-decoration: none;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                            Hot
                        </a>
                    @endif

                    @if(isset($sort) && $sort == 'best')
                        <div style="display: inline-flex; align-items: center; gap: 4px; padding: 7px 8px 7px 14px; border-radius: var(--radius-pill); background: var(--accent-gradient); font-size: 14px; font-weight: 600; color: white; white-space: nowrap;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                            Best
                            <a href="{{ route('home') }}" title="Reset"
                               style="display: flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; background: rgba(255,255,255,0.25); color: white; margin-left: 2px; transition: background 0.2s ease;"
                               onmouseover="this.style.background='rgba(255,255,255,0.45)'"
                               onmouseout="this.style.background='rgba(255,255,255,0.25)'">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </a>
                        </div>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'best']) }}" class="feed-tab" style="text-decoration: none;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                            Best
                        </a>
                    @endif

                    @auth
                        @if(Auth::user()->role === 'user')
                            <button class="new-post-cta" onclick="openModal('modalPost')">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Create Post</span>
                            </button>
                        @endif
                    @endauth
                </div>

                <div style="margin-left: auto; display: flex; gap: 8px;">
                    <button id="layoutToggleBtn" class="interaction-btn" style="padding: 8px;" onclick="toggleGridLayout()" title="Toggle Grid Layout">
                        <svg id="icon-grid" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        <svg id="icon-list" style="display: none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    </button>
                </div>
            </div>

            <div id="feed-container" class="feed-container">
            @forelse($posts as $post)
            <article class="post">
                <div class="post-header">
                    <div class="post-header-left">
                        <div class="community-avatar">
                            {{ substr($post->category->label ?? 'P', 0, 1) }}
                        </div>
                        <div>
                            <div class="post-community">p/{{ strtolower($post->category->label ?? 'general') }}</div>
                            <div class="post-meta">
                                u/{{ $post->user->username ?? 'user' }} • {{ $post->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    
                    @auth
                        @if(Auth::id() !== $post->user_id)
                        <a href="{{ route('messages.show', $post->user_id) }}" class="interaction-btn" style="padding: 6px 12px; border-radius: var(--radius-pill); font-size: 11px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            Message
                        </a>
                        @endif
                    @endauth
                </div>

                <h2 class="post-title">{{ $post->title }}</h2>
                <div class="post-content">
                    {{ Str::limit($post->content, 300) }}
                </div>

                @if($post->image_path)
                <div class="post-media">
                    <img src="{{ filter_var($post->image_path, FILTER_VALIDATE_URL) ? $post->image_path : asset('storage/' . $post->image_path) }}" alt="Post image">
                </div>
                @endif

                <div class="post-actions">
                    <div class="action-group">
                        @if(Auth::check())
                        <form action="{{ route('react.post', $post->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="type" value="TOP">
                            <button type="submit" class="action-btn upvote {{ Auth::check() && $post->reactions->where('user_id', Auth::id())->where('appreciation.type', 'TOP')->count() ? 'active' : '' }}" title="Top (Upvote)">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="18 15 12 9 6 15"></polyline>
                                </svg>
                            </button>
                        </form>
                        @endif

                        <span class="vote-count">{{ $post->reactions->where('appreciation.type', 'TOP')->count() - $post->reactions->where('appreciation.type', 'FLOP')->count() }}</span>

                        @if(Auth::check())
                        <form action="{{ route('react.post', $post->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="type" value="FLOP">
                            <button type="submit" class="action-btn downvote {{ Auth::check() && $post->reactions->where('user_id', Auth::id())->where('appreciation.type', 'FLOP')->count() ? 'active' : '' }}" title="Flop (Downvote)">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    <a href="{{ route('posts.show', $post->id) }}" class="interaction-btn">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                        </svg>
                        {{ $post->comments_count }} Comments
                    </a>
                    @if(Auth::check() && Auth::id() !== $post->user_id)
                    <form action="{{ route('posts.report', $post->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="interaction-btn" title="Report this content">
                             <svg viewBox="0 0 24 24" style="width: 18px; height: 18px;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                        </button>
                    </form>
                    @endif
                </div>
            </article>
            @empty
            <div style="text-align: center; padding: 48px; background: var(--bg-glass); border: 1px solid var(--border-glass); border-radius: var(--radius-lg); color: var(--text-muted);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 16px; opacity: 0.5;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                <p>No posts found. Be the first to share something!</p>
            </div>
            @endforelse
            </div>
@endsection
