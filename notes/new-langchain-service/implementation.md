# LangGraph Implementation for HireGlue Resume/Cover Letter Generator

## Implementation Plan

Here's a step-by-step implementation plan to integrate LangGraph with your current HireGlue system:

1. **Set Up Dependencies**

    - Install Python dependencies (langgraph, langchain, etc.)
    - Create a Python microservice or bridge
2. **Create LangGraph Components**

    - Implement the state schema
    - Create the graph structure
    - Convert existing services to node functions
3. **Deploy the Python Microservice**

    - Deploy as a FastAPI service
    - Set up proper error handling and logging
4. **Implement Laravel Integration**

    - Create the LangGraphService
    - Implement the controller
    - Add Nova action for generation
5. **Create UI Components**

    - Implement the Vue component for streaming progress
    - Add human-in-the-loop feedback interfaces
6. **Test and Refine**

    - Test the entire workflow
    - Compare results with the current implementation
    - Refine the graph structure as needed

## 12. Benefits of This Implementation

1. **State Persistence and Resumability**: The LangGraph implementation allows for saving and resuming generation at any point, which is useful for long-running generations or human-in-the-loop scenarios.
2. **Improved Visibility**: The streaming capabilities provide real-time updates on the generation process, making it more transparent to users.
3. **Enhanced Evaluation**: The structured feedback loop allows for systematic improvement of generated documents.
4. **Better Orchestration**: The graph structure makes the flow more explicit and easier to modify or extend.
5. **Scalability**: The separation between the Laravel app and the Python microservice allows for better scaling of computation-intensive tasks.

This LangGraph implementation maintains all the powerful features of your current system while adding structured workflow management, human-in-the-loop capabilities, and enhanced observability. The integration with your existing Laravel services is seamless, allowing you to leverage the best of both worlds.


## 1. Setting Up LangGraph Dependencies

First, let's set up the necessary dependencies:

```python


python

Copy

# requirements.txt additions
langgraph>=0.0.20
langchain>=0.1.0
langchain-openai>=0.0.5
pydantic>=2.5.0
```

## 2. State Schema Definition

Let's define the state schema that will be passed through our graph:

```python


python

Copy

# app/langgraph/schema.py
from typing import Dict, List, Optional, TypedDict, Any, Union

class SkillMatch(TypedDict):
    skill_id: int
    skill_name: str
    similarity: float
    proficiency: int
    years_experience: int

class ValidationResult(TypedDict):
    overall_score: int
    criteria: Dict[str, Dict[str, Union[int, str]]]
    summary: str
    suggestions: List[str]

class GenerationState(TypedDict):
    # Input data
    job_post_id: int
    user_id: int
    document_type: str  # 'resume' or 'cover_letter'
    
    # Analysis data
    skill_matches: Optional[Dict[str, List[SkillMatch]]]
    recommendations: Optional[Dict[str, Any]]
    
    # Generation data
    content_variations: Optional[List[Dict[str, Any]]]
    selected_variation: Optional[Dict[str, Any]]
    
    # Thread management
    thread_id: Optional[str]
    assistant_id: Optional[str]
    session_id: Optional[int]
    
    # Document data
    content: Optional[str]
    validation_result: Optional[ValidationResult]
    
    # Feedback data
    feedback: Optional[str]
    iterations: int
    
    # Final output
    final_document: Optional[Dict[str, Any]]
    pdf_path: Optional[str]
```

## 3. Core Graph Implementation

Now, let's create the main graph structure:

```python


python

Copy

# app/langgraph/graph.py
from langgraph.graph import StateGraph, END
from langgraph.checkpoint.sqlite import SqliteSaver
from typing import Dict, List, Annotated, TypedDict

from app.langgraph.schema import GenerationState
from app.langgraph.nodes import (
    initialize_state,
    analyze_requirements,
    generate_variations,
    select_best_variation,
    create_thread_session,
    generate_final_content,
    validate_document,
    process_feedback,
    generate_pdf,
    finalize_document
)

def create_generation_graph():
    # Initialize the graph with our state schema
    graph = StateGraph(GenerationState)
    
    # Add nodes to the graph
    graph.add_node("initialize", initialize_state)
    graph.add_node("analyze_requirements", analyze_requirements)
    graph.add_node("generate_variations", generate_variations)
    graph.add_node("select_best_variation", select_best_variation)
    graph.add_node("create_thread_session", create_thread_session)
    graph.add_node("generate_final_content", generate_final_content)
    graph.add_node("validate_document", validate_document)
    graph.add_node("process_feedback", process_feedback)
    graph.add_node("generate_pdf", generate_pdf)
    graph.add_node("finalize_document", finalize_document)
    
    # Define conditional routing logic
    def should_regenerate(state: GenerationState):
        # Check if we need to regenerate based on validation score
        if (state.get("validation_result") and 
            state["validation_result"]["overall_score"] < 8 and
            state["iterations"] < 3):
            return "process_feedback"
        else:
            return "generate_pdf"
    
    # Add edges to connect nodes
    graph.add_edge("initialize", "analyze_requirements")
    graph.add_edge("analyze_requirements", "generate_variations")
    graph.add_edge("generate_variations", "select_best_variation")
    graph.add_edge("select_best_variation", "create_thread_session")
    graph.add_edge("create_thread_session", "generate_final_content")
    graph.add_edge("generate_final_content", "validate_document")
    graph.add_edge("validate_document", should_regenerate)
    graph.add_edge("process_feedback", "generate_final_content")
    graph.add_edge("generate_pdf", "finalize_document")
    graph.add_edge("finalize_document", END)
    
    # Add persistence
    graph.set_checkpoint(SqliteSaver("./checkpoints/generation"))
    
    # Compile the graph
    return graph.compile()

# Create a singleton instance
generation_graph = create_generation_graph()
```

## 4. Node Implementations

Let's implement each node function that will integrate with your existing services:

```python


python

Copy

# app/langgraph/nodes.py
import logging
from typing import Dict, List, Optional, Any

from app.langgraph.schema import GenerationState
from app.Models.JobPost import JobPost
from app.Models.User import User
from app.Services.EmbeddingsService import EmbeddingsService
from app.Services.PromptEngineeringService import PromptEngineeringService
from app.Services.ThreadManagementService import ThreadManagementService
from app.Services.PDFService import PDFService
from app.Models.Resume import Resume
from app.Models.CoverLetter import CoverLetter
from app.Models.ThreadSession import ThreadSession

logger = logging.getLogger(__name__)

# Initialize services
embeddings_service = EmbeddingsService()
prompt_engineering = PromptEngineeringService()
thread_management = ThreadManagementService()
pdf_service = PDFService()

def initialize_state(state: GenerationState) -> GenerationState:
    """Initialize the state with JobPost and User information."""
    logger.debug(f"Initializing state for job_post_id={state['job_post_id']}, user_id={state['user_id']}")
    
    job_post = JobPost.find(state["job_post_id"])
    user = User.find(state["user_id"])
    
    if not job_post or not user:
        raise ValueError(f"Job post or user not found: job_post_id={state['job_post_id']}, user_id={state['user_id']}")
    
    # Set initial state
    return {
        **state,
        "iterations": 0,
    }

def analyze_requirements(state: GenerationState) -> GenerationState:
    """Analyze job requirements and perform skill matching."""
    logger.debug(f"Analyzing requirements for job_post_id={state['job_post_id']}")
    
    job_post = JobPost.find(state["job_post_id"])
    user = User.find(state["user_id"])
    
    # Find skill matches and generate recommendations
    skill_matches = embeddings_service.findSkillMatches(user, job_post)
    recommendations = embeddings_service.generateRecommendations(user, job_post)
    
    return {
        **state,
        "skill_matches": skill_matches,
        "recommendations": recommendations
    }

def generate_variations(state: GenerationState) -> GenerationState:
    """Generate multiple content variations using PromptEngineeringService."""
    logger.debug(f"Generating content variations for {state['document_type']}")
    
    job_post = JobPost.find(state["job_post_id"])
    user = User.find(state["user_id"])
    
    # Generate content variations
    variations = prompt_engineering.generateWithMultiStepWorkflow(
        job_post, 
        user, 
        state["document_type"]
    )
    
    return {
        **state,
        "content_variations": variations
    }

def select_best_variation(state: GenerationState) -> GenerationState:
    """Select the best variation based on scores."""
    logger.debug("Selecting best content variation")
    
    variations = state["content_variations"]
    selected = prompt_engineering.selectBestVariation(variations)
    
    return {
        **state,
        "selected_variation": selected
    }

def create_thread_session(state: GenerationState) -> GenerationState:
    """Create a thread session for generation."""
    logger.debug(f"Creating thread session for {state['document_type']}")
    
    job_post = JobPost.find(state["job_post_id"])
    user = User.find(state["user_id"])
    
    # Create appropriate session type
    if state["document_type"] == "resume":
        session = thread_management.startResumeSession(user, job_post)
    else:
        session = thread_management.startCoverLetterSession(user, job_post)
    
    return {
        **state,
        "thread_id": session.thread_id,
        "assistant_id": session.assistant_id,
        "session_id": session.id
    }

def generate_final_content(state: GenerationState) -> GenerationState:
    """Generate final content with the assistant."""
    logger.debug(f"Generating final content for {state['document_type']}")
    
    session = ThreadSession.find(state["session_id"])
    
    # Add context from our semantic analysis and variations
    context_message = prepare_context_message(
        state["recommendations"], 
        state["selected_variation"], 
        state["document_type"]
    )
    
    thread_management.addMessage(session.thread_id, context_message)
    
    # Add content to refine from the selected variation
    content_to_refine = state["selected_variation"]["content"]
    thread_management.addMessage(session.thread_id, f"Here's the content to refine:\n\n{content_to_refine}")
    
    # If there's feedback from a previous iteration, add it
    if state.get("feedback"):
        thread_management.addMessage(
            session.thread_id, 
            f"Please incorporate this feedback in your refinement:\n\n{state['feedback']}"
        )
    
    # Generate content
    content = thread_management.generateContent(session)
    
    return {
        **state,
        "content": content,
        "iterations": state["iterations"] + 1
    }

def validate_document(state: GenerationState) -> GenerationState:
    """Validate the generated document against rules."""
    logger.debug(f"Validating {state['document_type']} content")
    
    job_post = JobPost.find(state["job_post_id"])
    content = state["content"]
    document_type = state["document_type"]
    
    # Validate document
    validation_result = thread_management.validateDocument(content, document_type, job_post)
    
    return {
        **state,
        "validation_result": validation_result
    }

def process_feedback(state: GenerationState) -> GenerationState:
    """Process validation results and generate feedback for improvement."""
    logger.debug(f"Processing feedback for {state['document_type']}")
    
    validation = state["validation_result"]
    
    # Generate feedback based on validation results
    feedback = "Please improve the document based on the following feedback:\n\n"
    
    # Add feedback from validation criteria
    for criterion, details in validation["criteria"].items():
        if details["score"] < 7:
            feedback += f"- {criterion}: {details['feedback']}\n"
    
    # Add suggestions
    if validation["suggestions"]:
        feedback += "\nSuggestions:\n"
        for suggestion in validation["suggestions"]:
            feedback += f"- {suggestion}\n"
    
    # Add overall assessment
    feedback += f"\nOverall assessment: {validation['summary']}"
    
    return {
        **state,
        "feedback": feedback
    }

def generate_pdf(state: GenerationState) -> GenerationState:
    """Generate PDF for the final document."""
    logger.debug(f"Generating PDF for {state['document_type']}")
    
    job_post = JobPost.find(state["job_post_id"])
    user = User.find(state["user_id"])
    content = state["content"]
    document_type = state["document_type"]
    
    # Create document record
    if document_type == "resume":
        document = Resume.create({
            'user_id': user.id,
            'job_post_id': job_post.id,
            'thread_session_id': state["session_id"],
            'content': content,
            'word_count': count_words(content),
            'skills_included': state["recommendations"].get("key_skills_to_emphasize", []),
            'rule_compliance': state["validation_result"],
            'generation_metadata': {
                'template_used': state["selected_variation"]["template"],
                'variations_score': state["selected_variation"]["score"],
                'iterations': state["iterations"],
                'semantic_match_data': {
                    'recommendations': state["recommendations"],
                }
            }
        })
        
        # Generate PDF
        pdf_path = pdf_service.generateResumePDF(document)
    else:
        document = CoverLetter.create({
            'user_id': user.id,
            'job_post_id': job_post.id,
            'thread_session_id': state["session_id"],
            'content': content,
            'word_count': count_words(content),
            'rule_compliance': state["validation_result"],
            'generation_metadata': {
                'template_used': state["selected_variation"]["template"],
                'variations_score': state["selected_variation"]["score"],
                'iterations': state["iterations"],
                'semantic_match_data': {
                    'recommendations': state["recommendations"],
                }
            }
        })
        
        # Generate PDF
        pdf_path = pdf_service.generateCoverLetterPDF(document)
    
    return {
        **state,
        "pdf_path": pdf_path,
        "final_document": {
            "id": document.id,
            "type": document_type,
            "pdf_path": pdf_path
        }
    }

def finalize_document(state: GenerationState) -> GenerationState:
    """Finalize the document generation process."""
    logger.debug(f"Finalizing {state['document_type']} generation")
    
    # Could add more cleanup, notification, or statistics generation here
    
    return {
        **state,
        "completed": True
    }

# Helper functions
def prepare_context_message(recommendations, selected_result, document_type):
    """Prepare context message for the assistant."""
    message = "I've performed semantic analysis and generated variations for this {document_type}. Please use this context to refine the content I'll provide:\n\n"
    
    message += "# Semantic Analysis Recommendations\n"
    
    if recommendations.get("key_skills_to_emphasize"):
        message += "## Key Skills to Emphasize\n"
        for skill in recommendations["key_skills_to_emphasize"]:
            message += f"- {skill}\n"
        message += "\n"
    
    # ... (continuing with other sections)
    
    message += "# Variation Information\n"
    message += f"Template used: {selected_result['template']}\n"
    message += f"Overall score: {selected_result['score']}/100\n\n"
    
    # ... (continuing with strengths and weaknesses)
    
    message += "Please use this analysis to improve the content while maintaining its strengths."
    
    return message

def count_words(text):
    """Count words in the given text."""
    return len(text.split())
```

## 5. Integration with Laravel

To integrate this Python-based LangGraph implementation with your Laravel application, we'll need to create a service that can invoke the LangGraph workflow. This can be done through a Python microservice that your Laravel app communicates with.

Here's a simple FastAPI service that exposes the LangGraph workflow:

```python


python

Copy

# langgraph_service.py
import uvicorn
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import Dict, Any, Optional

from app.langgraph.graph import generation_graph
from app.langgraph.schema import GenerationState

app = FastAPI()

class GenerationRequest(BaseModel):
    job_post_id: int
    user_id: int
    document_type: str
    config: Optional[Dict[str, Any]] = None

@app.post("/generate")
async def generate_document(request: GenerationRequest):
    try:
        # Create initial state
        initial_state: GenerationState = {
            "job_post_id": request.job_post_id,
            "user_id": request.user_id,
            "document_type": request.document_type,
            "iterations": 0
        }
        
        # Run the graph
        result = generation_graph.invoke(initial_state)
        
        # Return final result
        return {
            "success": True,
            "document": result.get("final_document"),
            "validation": result.get("validation_result"),
            "metrics": {
                "iterations": result.get("iterations"),
                "overall_score": result.get("validation_result", {}).get("overall_score")
            }
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/generate/stream")
async def stream_generation(request: GenerationRequest):
    try:
        # Create initial state
        initial_state: GenerationState = {
            "job_post_id": request.job_post_id,
            "user_id": request.user_id,
            "document_type": request.document_type,
            "iterations": 0
        }
        
        # Stream events from the graph execution
        for event in generation_graph.stream(initial_state):
            # Yield each event as a server-sent event
            yield {
                "event": "update",
                "data": {
                    "node": event.get("node"),
                    "state": {
                        "iterations": event.get("state", {}).get("iterations"),
                        "current_step": event.get("node"),
                        "validation_score": event.get("state", {}).get("validation_result", {}).get("overall_score")
                    }
                }
            }
        
        # Yield final result
        final_state = generation_graph.get_state()
        yield {
            "event": "complete",
            "data": {
                "document": final_state.get("final_document"),
                "validation": final_state.get("validation_result"),
                "metrics": {
                    "iterations": final_state.get("iterations"),
                    "overall_score": final_state.get("validation_result", {}).get("overall_score")
                }
            }
        }
    except Exception as e:
        yield {
            "event": "error",
            "data": {"error": str(e)}
        }

@app.post("/feedback")
async def provide_feedback(request: Dict[str, Any]):
    try:
        # Get existing state
        state_id = request.get("state_id")
        feedback = request.get("feedback")
        
        if not state_id or not feedback:
            raise HTTPException(status_code=400, detail="Missing state_id or feedback")
        
        # Load state from checkpoint
        state = generation_graph.get_state(state_id)
        
        if not state:
            raise HTTPException(status_code=404, detail="State not found")
        
        # Update state with feedback
        state["feedback"] = feedback
        
        # Continue graph execution from process_feedback node
        result = generation_graph.invoke(state, {"configurable": {"start_from": "process_feedback"}})
        
        return {
            "success": True,
            "document": result.get("final_document"),
            "validation": result.get("validation_result")
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    uvicorn.run("langgraph_service:app", host="0.0.0.0", port=8000)
```

## 6. Laravel Service Integration

Now, let's create a Laravel service to communicate with our LangGraph microservice:

```php


php

Copy

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\JobPost;
use App\Models\User;
use App\Models\Resume;
use App\Models\CoverLetter;
use Illuminate\Support\Facades\Log;
use Exception;

class LangGraphService
{
    protected string $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('services.langgraph.base_url', 'http://localhost:8000');
    }

    /**
     * Generate a document using LangGraph
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param string $type
     * @return array
     * @throws Exception
     */
    public function generateDocument(JobPost $jobPost, User $user, string $type): array
    {
        try {
            Log::debug("Starting LangGraph document generation", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
                'type' => $type,
            ]);

            $response = Http::post("{$this->apiBaseUrl}/generate", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
                'document_type' => $type,
            ]);

            if (!$response->successful()) {
                Log::error("LangGraph API error", [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
                throw new Exception("LangGraph API error: " . ($response->json()['detail'] ?? 'Unknown error'));
            }

            $result = $response->json();

            Log::debug("LangGraph generation completed", [
                'job_post_id' => $jobPost->id,
                'document_id' => $result['document']['id'] ?? null,
                'type' => $type,
                'score' => $result['metrics']['overall_score'] ?? null,
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error("LangGraph generation failed", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Stream document generation progress
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param string $type
     * @return \Generator
     */
    public function streamGeneration(JobPost $jobPost, User $user, string $type): \Generator
    {
        try {
            Log::debug("Starting LangGraph document generation stream", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
                'type' => $type,
            ]);

            $response = Http::withOptions([
                'stream' => true,
            ])->post("{$this->apiBaseUrl}/generate/stream", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
                'document_type' => $type,
            ]);

            foreach ($response->getBody() as $chunk) {
                // Parse the SSE data
                $data = json_decode($chunk, true);
                yield $data;

                if ($data['event'] === 'complete') {
                    Log::debug("LangGraph streaming generation completed", [
                        'job_post_id' => $jobPost->id,
                        'document_id' => $data['data']['document']['id'] ?? null,
                        'type' => $type,
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error("LangGraph streaming generation failed", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);

            yield [
                'event' => 'error',
                'data' => ['error' => $e->getMessage()],
            ];
        }
    }

    /**
     * Provide feedback for document improvement
     *
     * @param string $stateId
     * @param string $feedback
     * @return array
     * @throws Exception
     */
    public function provideFeedback(string $stateId, string $feedback): array
    {
        try {
            Log::debug("Providing feedback to LangGraph", [
                'state_id' => $stateId,
                'feedback_length' => strlen($feedback),
            ]);

            $response = Http::post("{$this->apiBaseUrl}/feedback", [
                'state_id' => $stateId,
                'feedback' => $feedback,
            ]);

            if (!$response->successful()) {
                Log::error("LangGraph feedback API error", [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
                throw new Exception("LangGraph API error: " . ($response->json()['detail'] ?? 'Unknown error'));
            }

            $result = $response->json();

            Log::debug("LangGraph feedback processing completed", [
                'document_id' => $result['document']['id'] ?? null,
                'score' => $result['validation']['overall_score'] ?? null,
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error("LangGraph feedback processing failed", [
                'state_id' => $stateId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
```

## 7. Laravel Controller for LangGraph Integration

```php


php

Copy

<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Services\LangGraphService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DocumentGenerationController extends Controller
{
    protected LangGraphService $langGraphService;

    public function __construct(LangGraphService $langGraphService)
    {
        $this->langGraphService = $langGraphService;
    }

    /**
     * Generate a document using LangGraph
     *
     * @param Request $request
     * @param int $jobPostId
     * @return Response
     */
    public function generate(Request $request, int $jobPostId)
    {
        // Validate request
        $request->validate([
            'document_type' => 'required|in:resume,cover_letter',
        ]);

        $jobPost = JobPost::findOrFail($jobPostId);
        $user = Auth::user();
        $type = $request->input('document_type');

        // Generate document
        $result = $this->langGraphService->generateDocument($jobPost, $user, $type);

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' generated successfully',
            'data' => $result,
        ]);
    }

    /**
     * Stream generation progress
     *
     * @param Request $request
     * @param int $jobPostId
     * @return Response
     */
    public function stream(Request $request, int $jobPostId)
    {
        // Validate request
        $request->validate([
            'document_type' => 'required|in:resume,cover_letter',
        ]);

        $jobPost = JobPost::findOrFail($jobPostId);
        $user = Auth::user();
        $type = $request->input('document_type');

        // Set up streaming response
        return response()->stream(
            function () use ($jobPost, $user, $type) {
                foreach ($this->langGraphService->streamGeneration($jobPost, $user, $type) as $data) {
                    echo 'data: ' . json_encode($data) . "\n\n";
                    ob_flush();
                    flush();
                }
            },
            200,
            [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'X-Accel-Buffering' => 'no',
            ]
        );
    }

    /**
     * Provide feedback for document improvement
     *
     * @param Request $request
     * @return Response
     */
    public function feedback(Request $request)
    {
        // Validate request
        $request->validate([
            'state_id' => 'required|string',
            'feedback' => 'required|string',
        ]);

        $stateId = $request->input('state_id');
        $feedback = $request->input('feedback');

        // Process feedback
        $result = $this->langGraphService->provideFeedback($stateId, $feedback);

        return response()->json([
            'success' => true,
            'message' => 'Feedback processed successfully',
            'data' => $result,
        ]);
    }
}
```

## 8. Nova Action for LangGraph-based Generation

```php


php

Copy

<?php

namespace App\Nova\Actions;

use App\Services\LangGraphService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class GenerateWithLangGraph extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $langGraphService = app(LangGraphService::class);

        foreach ($models as $jobPost) {
            try {
                $result = $langGraphService->generateDocument(
                    $jobPost, 
                    auth()->user(), 
                    $fields->document_type
                );

                $documentType = ucfirst($fields->document_type);
                $documentId = $result['document']['id'] ?? 'unknown';
                $score = $result['metrics']['overall_score'] ?? 'N/A';

                return Action::message("{$documentType} generated successfully! ID: {$documentId}, Score: {$score}/10");
            } catch (\Exception $e) {
                return Action::danger("Failed to generate {$fields->document_type}: {$e->getMessage()}");
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make('Document Type', 'document_type')
                ->options([
                    'resume' => 'Resume',
                    'cover_letter' => 'Cover Letter',
                ])
                ->rules('required')
                ->default('resume'),
        ];
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Generate with LangGraph';
    }
}
```

## 9. Routes Configuration

```php


php

Copy

// routes/api.php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/jobs/{jobPost}/generate', [DocumentGenerationController::class, 'generate']);
    Route::get('/jobs/{jobPost}/generate/stream', [DocumentGenerationController::class, 'stream']);
    Route::post('/generation/feedback', [DocumentGenerationController::class, 'feedback']);
});
```

## 10. Human-in-the-Loop Vue Component

```vue


vue

Copy

<!-- resources/js/components/LangGraphGenerationMonitor.vue -->
<template>
  <div class="generation-monitor">
    <div class="status-panel">
      <h3>Generation Progress</h3>
      <div class="progress-bar">
        <div 
          class="progress" 
          :style="{ width: `${progress}%` }" 
          :class="{ 'completed': isCompleted }"
        ></div>
      </div>
      <div class="status">
        <span class="status-label">Status:</span>
        <span class="status-value">{{ currentStatus }}</span>
      </div>
      <div class="metrics" v-if="metrics">
        <div class="metric">
          <span class="metric-label">Iterations:</span>
          <span class="metric-value">{{ metrics.iterations || 0 }}</span>
        </div>
        <div class="metric">
          <span class="metric-label">Score:</span>
          <span 
            class="metric-value" 
            :class="getScoreClass(metrics.overall_score)"
          >{{ metrics.overall_score || 'N/A' }}/10</span>
        </div>
      </div>
    </div>

    <div class="feedback-panel" v-if="needsFeedback">
      <h3>Document Needs Improvement</h3>
      <p>The generated document needs some improvements. Please provide feedback:</p>
      <div class="validation-results" v-if="validationResult">
        <h4>Validation Results</h4>
        <div 
          v-for="(details, criterion) in validationResult.criteria" 
          :key="criterion"
          class="criterion"
        >
          <div class="criterion-header">
            <span class="criterion-name">{{ criterion }}</span>
            <span 
              class="criterion-score" 
              :class="getScoreClass(details.score)"
            >{{ details.score }}/10</span>
          </div>
          <p class="criterion-feedback">{{ details.feedback }}</p>
        </div>
        
        <div class="suggestions" v-if="validationResult.suggestions?.length">
          <h4>Suggestions</h4>
          <ul>
            <li v-for="(suggestion, index) in validationResult.suggestions" :key="index">
              {{ suggestion }}
            </li>
          </ul>
        </div>
      </div>
      
      <textarea 
        v-model="feedbackText" 
        placeholder="Enter your feedback here..."
        rows="5"
        class="feedback-input"
      ></textarea>
      
      <div class="actions">
        <button 
          class="regenerate-btn" 
          @click="submitFeedback" 
          :disabled="!feedbackText || isProcessing"
        >
          Regenerate with Feedback
        </button>
        <button 
          class="accept-btn" 
          @click="acceptDocument" 
          :disabled="isProcessing"
        >
          Accept Document
        </button>
      </div>
    </div>
    
    <div class="document-preview" v-if="documentContent">
      <h3>Document Preview</h3>
      <div class="preview-content" v-html="documentContent"></div>
      
      <div class="actions" v-if="isCompleted">
        <a 
          :href="documentPdfUrl" 
          class="download-btn" 
          target="_blank"
        >
          Download PDF
        </a>
      </div>
    </div>
    
    <div class="error-panel" v-if="error">
      <h3>Error</h3>
      <p class="error-message">{{ error }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    jobPostId: {
      type: Number,
      required: true
    },
    documentType: {
      type: String,
      required: true,
      validator: value => ['resume', 'cover_letter'].includes(value)
    }
  },
  
  data() {
    return {
      currentNode: null,
      progress: 0,
      isCompleted: false,
      isProcessing: false,
      error: null,
      stateId: null,
      documentContent: null,
      documentPdfUrl: null,
      metrics: null,
      validationResult: null,
      needsFeedback: false,
      feedbackText: '',
      
      // Node progress mapping
      nodeProgress: {
        'initialize': 5,
        'analyze_requirements': 15,
        'generate_variations': 35,
        'select_best_variation': 45,
        'create_thread_session': 50,
        'generate_final_content': 70,
        'validate_document': 85,
        'process_feedback': 90,
        'generate_pdf': 95,
        'finalize_document': 100
      }
    };
  },
  
  computed: {
    currentStatus() {
      if (this.error) return 'Error';
      if (this.isCompleted) return 'Completed';
      if (this.needsFeedback) return 'Needs Feedback';
      if (this.isProcessing) return 'Processing';
      
      switch (this.currentNode) {
        case 'initialize': return 'Initializing...';
        case 'analyze_requirements': return 'Analyzing Job Requirements...';
        case 'generate_variations': return 'Generating Content Variations...';
        case 'select_best_variation': return 'Selecting Best Variation...';
        case 'create_thread_session': return 'Creating Assistant Session...';
        case 'generate_final_content': return 'Generating Final Content...';
        case 'validate_document': return 'Validating Document...';
        case 'process_feedback': return 'Processing Feedback...';
        case 'generate_pdf': return 'Generating PDF...';
        case 'finalize_document': return 'Finalizing Document...';
        default: return 'Preparing...';
      }
    }
  },
  
  mounted() {
    this.startGeneration();
  },
  
  methods: {
    startGeneration() {
      this.isProcessing = true;
      this.error = null;
      
      const eventSource = new EventSource(`/api/jobs/${this.jobPostId}/generate/stream?document_type=${this.documentType}`);
      
      eventSource.addEventListener('update', (event) => {
        const data = JSON.parse(event.data);
        this.currentNode = data.state.current_step;
        this.progress = this.nodeProgress[this.currentNode] || 0;
        this.stateId = data.state.state_id;
        
        if (data.state.validation_score) {
          this.metrics = {
            overall_score: data.state.validation_score
          };
        }
        
        if (data.state.iterations) {
          this.metrics = {
            ...this.metrics,
            iterations: data.state.iterations
          };
        }
      });
      
      eventSource.addEventListener('complete', (event) => {
        const data = JSON.parse(event.data);
        this.isProcessing = false;
        this.isCompleted = true;
        this.progress = 100;
        
        this.documentContent = data.document.content;
        this.documentPdfUrl = data.document.pdf_path;
        this.validationResult = data.validation;
        this.metrics = data.metrics;
        
        // Check if needs feedback
        if (this.metrics.overall_score < 8) {
          this.needsFeedback = true;
        }
        
        eventSource.close();
      });
      
      eventSource.addEventListener('error', (event) => {
        this.isProcessing = false;
        this.error = event.data?.error || 'An error occurred during generation';
        eventSource.close();
      });
    },
    
    submitFeedback() {
      if (!this.feedbackText) return;
      
      this.isProcessing = true;
      
      axios.post('/api/generation/feedback', {
        state_id: this.stateId,
        feedback: this.feedbackText
      })
      .then(response => {
        const data = response.data.data;
        
        this.isProcessing = false;
        this.documentContent = data.document.content;
        this.documentPdfUrl = data.document.pdf_path;
        this.validationResult = data.validation;
        
        if (data.validation.overall_score >= 8) {
          this.needsFeedback = false;
          this.isCompleted = true;
        }
        
        this.feedbackText = '';
      })
      .catch(error => {
        this.isProcessing = false;
        this.error = error.response?.data?.message || 'Failed to process feedback';
      });
    },
    
    acceptDocument() {
      this.needsFeedback = false;
      this.isCompleted = true;
    },
    
    getScoreClass(score) {
      if (!score) return '';
      if (score >= 8) return 'score-good';
      if (score >= 6) return 'score-average';
      return 'score-poor';
    }
  }
}
</script>

<style scoped>
.generation-monitor {
  display: flex;
  flex-direction: column;
  gap: 20px;
  max-width: 800px;
  margin: 0 auto;
}

.status-panel, .feedback-panel, .document-preview, .error-panel {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  padding: 20px;
}

.progress-bar {
  height: 8px;
  background-color: #e0e0e0;
  border-radius: 4px;
  margin: 10px 0;
  overflow: hidden;
}

.progress {
  height: 100%;
  background-color: #4299e1;
  transition: width 0.3s ease;
}

.progress.completed {
  background-color: #48bb78;
}

.status, .metrics {
  display: flex;
  margin-top: 10px;
}

.status-label, .metric-label {
  font-weight: 600;
  margin-right: 8px;
  min-width: 80px;
}

.metrics {
  display: flex;
  gap: 20px;
}

.feedback-input {
  width: 100%;
  margin: 15px 0;
  padding: 10px;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  font-family: inherit;
}

.actions {
  display: flex;
  gap: 10px;
  margin-top: 15px;
}

button, .download-btn {
  padding: 8px 16px;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
}

.regenerate-btn {
  background-color: #4299e1;
  color: white;
  border: none;
}

.accept-btn {
  background-color: #48bb78;
  color: white;
  border: none;
}

.download-btn {
  background-color: #2d3748;
  color: white;
  display: inline-block;
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.preview-content {
  margin-top: 15px;
  padding: 15px;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  max-height: 500px;
  overflow-y: auto;
}

.error-message {
  color: #e53e3e;
}

.validation-results {
  margin: 15px 0;
}

.criterion {
  margin-bottom: 10px;
  padding-bottom: 10px;
  border-bottom: 1px solid #e2e8f0;
}

.criterion-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.criterion-name {
  font-weight: 600;
}

.score-good {
  color: #48bb78;
}

.score-average {
  color: #ed8936;
}

.score-poor {
  color: #e53e3e;
}

.suggestions ul {
  margin-top: 5px;
  padding-left: 20px;
}
</style>
```
